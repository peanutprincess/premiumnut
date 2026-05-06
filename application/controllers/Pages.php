<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require FCPATH . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pages extends CI_Controller{

	public function __construct() {
		Parent::__construct();
		$this->load->library('session');

		$this->load->model("page_model");
		$this->load->model("order_model");
		$this->load->model("admin_model");
		$this->load->model("customer_model");

		/*
		$config = array(
			'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
			'smtp_host' => 'smtp.gmail.com', 
			'smtp_port' => 587, //465 ssl, 587  tls
			'smtp_user' => 'pack@premiumnut.com',
			'smtp_pass' => 'premium38',
			'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
			'mailtype' => 'html', //plaintext 'text' mails or 'html'
			'smtp_timeout' => '30', //in seconds
			'charset' => 'utf-8',
			'dsn' => TRUE,
			'validate' => TRUE
		);
		
			
		$config = array(
			'protocol' => 'mail', // 'mail', 'sendmail', or 'smtp'
			'smtp_host' => 'mail.premiumnut.com', 
			'smtp_port' => 587, //465 ssl, 587  tls
			'smtp_user' => 'pack@premiumnut.com',
			'smtp_pass' => '.9@zA!^%,#WR',
			'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
			'mailtype' => 'html', //plaintext 'text' mails or 'html'
			'smtp_timeout' => '30', //in seconds
			'charset' => 'utf-8',
			'dsn' => TRUE,
			'validate' => TRUE
		);
		*/
		$config = array(
			'mailtype' => 'html', //plaintext 'text' mails or 'html'
		);

		$this->load->library('email', $config);

		$this->email->set_newline("\r\n");
	}

	public function view($page = 'home')
	{
		if(!file_exists(APPPATH.'views/pages/'.$page.'.php'))
		{
			show_404();
		}
		$data['title'] = ucfirst($page);
		$data['level'] = $this->session->userdata('level');

		if($page == 'guide') {
			if(isset($_GET['weight'])){
				$weight_type_id = $_GET['weight'];
				$data['weight_name'] = $this->page_model->get_weight_name_by_id($weight_type_id);
				$data['products_list'] = $this->page_model->get_products_guide($weight_type_id);
				foreach($data['products_list'] as &$product){
					$product_price_level_data['product_id'] = $product['id'];
					$product_price_level_data['price_level_id'] = $data['level'];
	
					$product_price_level = $this->page_model->get_product_price_level_by_productid_pricelevel($product_price_level_data);
					if($product_price_level){
						$product['price_level_price'] = $product_price_level[0]->price;
					}
				}
			}else{
				$page = 'home';
			}
		}

		if (!$this->session->userdata('username')) {
			$page = 'home';
		}

		/* mail test config start */
		/*
		if ($page == 'guide') {

			$config['mailtype'] = 'html'; // or html

			$this->email->initialize($config);

			$emailhtml = '<p>New Order # '.$new_order_id.' has been placed at Premiumnut Order App, Following are the details:</p><br><br>';
			foreach($addData as $value => $key) {
				$emailhtml .= "$value: $key<br>";
			}

			$this->email->from('no-reply@premiumnut.com', 'Premiumnut Order');

			$this->email->to('orders@premiumnut.com');
			$this->email->cc('maverik_90@hotmail.com');
			
			$this->email->subject('New Order has been placed at Premiumnut Order App');
			$this->email->message($emailhtml);
			
			$this->email->send();

		}	
		*/	
		/* mail test config end */


		$this->load->view('templates/header');
		$this->load->view('pages/'.$page, $data);
		$this->load->view('templates/footer'); 
		
	}

	public function products_list(){
		$products = $this->order_model->get_products_list();
		if( $this->session->userdata('level') >= '0' ){
			echo json_encode($products);
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
	}

    public function get_product_info(){
		$pro_id = array_keys($_GET);
        $productdata = $this->order_model->get_product_info_by_id($pro_id[0]);
		$ulevel = $this->session->userdata('level');
		if( $ulevel >= '0' ){
			
			if($ulevel > 2){
				$product_price_level_data['product_id'] = $productdata['id'];
				$product_price_level_data['price_level_id'] =$ulevel;
				$product_price_level = $this->page_model->get_product_price_level_by_productid_pricelevel($product_price_level_data);
				$product_level_price = $product_price_level[0]->price;
			}
			elseif($ulevel == 1 || $ulevel == 0){
				$product_level_price = $productdata['level1price'];
			}
			elseif($ulevel == 2){
				$product_level_price = $productdata['level2price'];
			}
			else{
				$product_level_price = 0;
			}

			$productdataArray = array(
				'id' => $productdata['id'],
				'item_name' => $productdata['item_name'],
				'master_id' => $productdata['master_id'],
				'description' => $productdata['description'],
				'u-m' => $productdata['u-m'],
				'price' => $product_level_price,
				'status' => $productdata['status'],
			);
			echo json_encode($productdataArray);
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found"));
		}
		exit();
    }

    public function get_product_edit_info(){
		$pro_id = array_keys($_GET);
        $productdata = $this->order_model->get_product_info_by_id($pro_id[0]);
		$ulevel = $this->session->userdata('level');

		if(is_numeric($productdata['u-m'])){
			$um_name = $this->order_model->get_um_name_by_id($productdata['u-m']);
			$um_id = $productdata['u-m'];
		}
		else{
			$um_id = $this->order_model->get_um_id_by_name($productdata['u-m']);
			$um_name = $productdata['u-m'];
		}

		if( $ulevel >= '0' ){
			
			$productdataArray = array(
				'id' => $productdata['id'],
				'name' => $productdata['item_name'],
				'master_id' => $productdata['master_id'],
				'description' => $productdata['description'],
				'um_id' => $um_id,
				'um_name' => $um_name,
				'price1' =>  $productdata['level1price'],
				'price2' =>  $productdata['level2price'],
				'product_type' =>  $productdata['product_type'],
				'weight_type' =>  $productdata['weight_type'],
				'status' => $productdata['status']
			);

			$price_level_list = $this->admin_model->get_all_price_level();
			$price_count = 0;
			foreach($price_level_list as $key =>$price_level){
				//echo 'price_level: '.$price_level['name'];

				$product_level_price = '';

				
				$product_price_level_data['product_id'] = $productdata['id'];
				$product_price_level_data['price_level_id'] = $price_level['id'];

				$product_price_level = $this->page_model->get_product_price_level_by_productid_pricelevel($product_price_level_data);
				if($product_price_level){
					$product_level_price = $product_price_level[0]->price;
				}

				$productdataArray['price_level'][$key]['id'] = $price_level['id'];
				$productdataArray['price_level'][$key]['name'] = $price_level['name'];
				$productdataArray['price_level'][$key]['price']  = $product_level_price;

				/*if(isset($data_get['price'.$plid]) && $data_get['price'.$plid] != null){
				
				}*/
				
				$price_count++;
				$productdataArray['price_count'] = $price_count;
			}
			

			echo json_encode($productdataArray);
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function check_product_by_master_id(){
		$item_master_id = $_GET['master_id'];
		$productdata = $this->page_model->get_product_by_master_id_query($item_master_id);
		echo json_encode($productdata);
	}

	public function get_product_by_master_id(){
		$item_master_id =  $_GET['item_master_id'];
		$weight_type =  $_GET['weight_type'];
		$product_type = $_GET['product_type'];

		$ulevel = $this->session->userdata('level');
		if( $ulevel == 0 ){
			$productdata = $this->page_model->get_product_by_master_id_query($item_master_id);
			
			$weight_id = $this->page_model->get_weight_type_by_name($weight_type);
			$product_id = $this->page_model->get_product_type_by_name($product_type);

			//$productdataupdate = $this->page_model->update_product_by_master_id_query($item_master_id,$weight_id,$product_id);
			//echo json_encode($productdata);
			//echo json_encode($productdataupdate);
		}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
	}

	public function get_product_info_order(){
		$pro_id = array_keys($_GET);
        $productdata = $this->order_model->get_product_info_by_id($pro_id[0]);
		$ulevel = $this->session->userdata('level');
		if( $ulevel >= '0' ){
			echo json_encode($productdata);
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function get_product_info_order_bulk(){
		$pro_ids = array_keys($_GET);
		$ids = $_GET['ids'];
		$qtys = $_GET['qtys'];
		$totals = $_GET['totals'];

		$all_product_ids = explode(',',$ids);
		$all_product_qtys = explode(',',$qtys);
		$all_product_totals = explode(',',$totals);

		$all_products_array = Array();
		foreach($all_product_ids as $key=>$single_product_id){
			if($single_product_id != ''){
				//echo 'single_product_id: '.$single_product_id;
				$productdata = $this->order_model->get_product_info_by_id($single_product_id);
				$productdata['product_qty'] = $all_product_qtys[$key];
				$productdata['product_total'] = $all_product_totals[$key];
				array_push($all_products_array, $productdata);
			}
		}
		//print_r($all_product_array);
		$ulevel = $this->session->userdata('level');
		if( $ulevel >= '0' ){
			echo json_encode($all_products_array);
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

    public function get_order_info(){
		//var_dump($_GET);
		$order_id = array_keys($_GET);
        $orderdata = $this->order_model->get_order_info_by_id($order_id[0]);
		$user_info = $this->admin_model->get_user_data($orderdata['user_id']);
		$order_level = '';

		if($this->session->userdata('level')  == '0' ){
			$order_level = '(Level <span id="order_level">'.$orderdata['order_level'].'</span>)';
		}

		if( $this->session->userdata('level') >= '0' ){
			$orderdatamod = array(
				'datetime' => gmdate("d-m-Y\ H:i\ ", $orderdata['datetime']),
                'id' => $orderdata['id'],
				'order_data' => $orderdata['order_data'],
				'order_id' => $orderdata['order_id'],
				'order_level' => $order_level,
				'order_total_price' => $orderdata['order_total_price'],
				'order_total_qty' => $orderdata['order_total_qty'],
				'po_number' => $orderdata['po_number'],
				'notes' => $orderdata['notes'],
				'status' => $orderdata['status'],
				'total_products' => $orderdata['total_products'],
				'user_id' => $orderdata['user_id'],
				'company' => $user_info[0]['company']
			);
			echo json_encode($orderdatamod);
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

    public function update_profile(){
		$u_data = $_GET;
		if( $this->session->userdata('level') >= '0' ){
            $update_data = $this->page_model->update_profile_info( $u_data );
            echo json_encode($update_data);
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function update_bulk_price(){
		extract($_POST);
		//echo '<br>update_bulk_price';

		$spreadsheet = new Spreadsheet();
		//echo '<br>Spreadsheet loaded';

		if (isset($_FILES['file']['name']) && !isset($_POST['saveconfirm'])) {

			$allowedFileType = [
				'application/vnd.ms-excel',
				'text/xls',
				'text/xlsx',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			];
		
			if (in_array($_FILES["file"]["type"], $allowedFileType)) {
		
				$targetPath = 'assets/imports/' . $_FILES['file']['name'];
				move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

				$arr_file = explode('.', $targetPath);
				$extension = end($arr_file);
				if('csv' == $extension){
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
				}elseif('xls' == $extension){
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				}else {
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				}
				$spreadsheet = $reader->load($targetPath);
				$sheetData = $spreadsheet->getActiveSheet()->toArray();
				$counter = 0;

				$html = 
				 '<table border="1px" style="margin:auto;width:100%;">
				 	<thead>
						<tr>
							<th>Sr No</th>
							<th>Data</th>
							<th>Master ID</th>
							<th>Price 1</th>
							<th>Price 2</th>
							<th>Status</th>
						</tr>
				 	</thead>
				 <tbody>';

				foreach($sheetData as $row){
					if(isset($row[3]) && $row[3] != 'MasterID' && $row[3] != '#VALUE!'){
						$product_master_ID = $row[3];
						$product_price1 = $row[10];
						$product_price2 = $row[12];
						$status1 = '-';
						$status2 = '-';

						$productdata = $this->page_model->get_product_by_master_id_query($product_master_ID);

						// echo "<pre>";
						// 	echo "<code>";
						// 		print_r($productdata);
						// 	echo "</code>";
						// echo "</pre>";


						if(isset($productdata) && $productdata != null){
							if($row[12] != $productdata[0]->level1price || $row[10] != $productdata[0]->level2price){
								$counter++;
								if($row[12] != $productdata[0]->level1price){ $status1 = 'P1 Different'; }
								if($row[10] != $productdata[0]->level2price){ $status2 = 'P2 Different'; }

								$html .=
								'<tr>
									<td>'.$counter.'</td>
									<td>Excel Data<br>Database Data</td>
									<td>'.$row[3].'<br>'.$productdata[0]->master_id.'</td>
									<td>'.$row[12].'<br>'.$productdata[0]->level1price.'</td>
									<td>'.$row[10].'<br>'.$productdata[0]->level2price.'</td>
									<td>'.$status1.'<br>'.$status2.'</td>
								</tr>';
							}
						}
						
					}
				}
				$html .=
				 '</tbody></table>';


				// echo "<pre>";
				// 	echo "<code>";
				// 		print_r($sheetData);
				// 	echo "</code>";
				// echo "</pre>";

				$htmlstr = str_replace(array("\r", "\n"), '', $html);
				$data['htmldata'] = $htmlstr;
				echo json_encode($data);
				exit;

			} else {
				$type = "error";
				$message = "Invalid File Type. Upload Excel File.";
			}
		}
		else{
			//echo  'saveconfirm: '.$_POST['saveconfirm'];
		}

		if( $this->session->userdata('level') >= '0' && isset($_POST['saveconfirm']) && $_POST['saveconfirm'] == 1){
			//echo 'data successfully saved to database - no operation done.';

			$allowedFileType = [
				'application/vnd.ms-excel',
				'text/xls',
				'text/xlsx',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			];

			if (in_array($_FILES["file"]["type"], $allowedFileType)) {
		
				$targetPath = 'assets/imports/' . $_FILES['file']['name'];
				move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

				$arr_file = explode('.', $targetPath);
				$extension = end($arr_file);
				if('csv' == $extension){
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
				}elseif('xls' == $extension){
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				}else {
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				}
				$spreadsheet = $reader->load($targetPath);
				$sheetData = $spreadsheet->getActiveSheet()->toArray();
				$counter = 0;

				foreach($sheetData as $row){
					if(isset($row[3]) && $row[3] != 'MasterID' && $row[3] != '#VALUE!'){
						$product_master_ID = $row[3];
						$product_price1 = $row[10];
						$product_price2 = $row[12];
						$status1 = '-';
						$status2 = '-';

						$productdata = $this->page_model->get_product_by_master_id_query($product_master_ID);

						if(isset($productdata) && $productdata != null){
							if($row[12] != $productdata[0]->level1price || $row[10] != $productdata[0]->level2price){
								$counter++;
								if($row[12] != $productdata[0]->level1price){ $status1 = 'P1 Different'; }
								if($row[10] != $productdata[0]->level2price){ $status2 = 'P2 Different'; }

								$pro_data['master_id'] = $row[3];
								$pro_data['price1'] = $row[12];
								$pro_data['price2'] = $row[10];

								$update_product_data = $this->page_model->update_product_prices( $pro_data );
								//echo '<br>update_product_data: '.$update_product_data ;
							}
						}
						
					}
				}

				//echo json_encode($data);
				//echo 'data successfully saved to database - save operation done.';
				$data['status'] = 200;
				echo json_encode($data);
				exit;

			} else {
				$type = "error";
				$message = "Invalid File Type. Upload Excel File.";
			}
	 	}
		else{
			die(header("HTTP/1.0 404 Not Found")); 
		}
		exit();
    }

	public function product_sheet_update(){
		extract($_POST);
		//echo '<br>update_bulk_price';

		$spreadsheet = new Spreadsheet();
		//echo '<br>Spreadsheet loaded';

		if (isset($_FILES['file']['name']) && !isset($_POST['saveconfirm'])) {

			$allowedFileType = [
				'application/vnd.ms-excel',
				'text/xls',
				'text/xlsx',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			];
		
			if (in_array($_FILES["file"]["type"], $allowedFileType)) {
		
				$targetPath = 'assets/imports/' . $_FILES['file']['name'];
				move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

				$arr_file = explode('.', $targetPath);
				$extension = end($arr_file);
				if('csv' == $extension){
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
				}elseif('xls' == $extension){
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
				}else {
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				}
				$spreadsheet = $reader->load($targetPath);
				$sheetData = $spreadsheet->getActiveSheet()->toArray();
				$counter = 0;

				$html = 
				 '<table border="1px" style="margin:auto;width:100%;">
				 	<thead>
						<tr>
							<th>Sr No</th>
							<th>Name</th>
							<th>Price 1</th>
							<th>Price 2</th>
							<th>Price CS</th>
							<th>Price Z</th>
							<th>Price BC</th>
							<th>Price PDR</th>
							<th>Price F17</th>
							<th>Price F15</th>
							<th>Price 0</th>
							<th>Price TJ</th>
							<th>Database</th>
						</tr>
				 	</thead>
				 <tbody>';

				foreach($sheetData as $row){
						// echo "<pre>";
						// 	echo "<code>";
						// 		print_r($productdata);
						// 	echo "</code>";
						// echo "</pre>";
						$product_data = '';
						$product_id = '';

						$counter++;
						$product_master_id = $row[4];

						$product_data = $this->page_model->get_product_by_master_id_query($product_master_id);

						if($product_data){
							$product_id = $product_data[0]->id;

							$price1 = '';
							$price2 = '';
							$priceCS = '';
							$priceZ = '';
							$priceBC = '';
							$pricePDR = '';
							$priceF17 = '';
							$priceF15 = '';
							$price0 = '';
							$priceTJ = '';

							$price1 = $row[12];
							$price2 = $row[10];
							$priceCS = $row[14];
							$priceZ = $row[16];
							$priceBC = $row[18];
							$pricePDR = $row[20];
							$priceF17 = $row[22];
							$priceF15 = $row[24];
							$price0 = $row[26];
							$priceTJ = $row[28];

					

							$html .=
							'<tr>
								<td>'.$counter.'</td>
								<td>'.$product_master_id.'</td>
								<td>'.$price1.'</td>
								<td>'.$price2.'</td>
								<td>'.$priceCS.'</td>
								<td>'.$priceZ.'</td>
								<td>'.$priceBC.'</td>
								<td>'.$pricePDR.'</td>
								<td>'.$priceF17.'</td>
								<td>'.$priceF15.'</td>
								<td>'.$price0.'</td>
								<td>'.$priceTJ.'</td>
								<td>'.
									 $product_id
									 /*'<br>';
									$new_product_price_level_data = array(
										'id' => '',
										'product_id' => $product_id,
										'price_level_id' => 3,
										'price' => $priceCS,
										'status' => 1
									);
									$this->admin_model->add_product_price_level_query($new_product_price_level_data);
									 '<br>';
									$new_product_price_level_data = array(
										'id' => '',
										'product_id' => $product_id,
										'price_level_id' => 4,
										'price' => $priceZ,
										'status' => 1
									);
									$this->admin_model->add_product_price_level_query($new_product_price_level_data);
									'<br>';
									$new_product_price_level_data = array(
										'id' => '',
										'product_id' => $product_id,
										'price_level_id' => 5,
										'price' => $priceBC,
										'status' => 1
									);
									$this->admin_model->add_product_price_level_query($new_product_price_level_data);
									'<br>';
									$new_product_price_level_data = array(
										'id' => '',
										'product_id' => $product_id,
										'price_level_id' => 6,
										'price' => $pricePDR,
										'status' => 1
									);
									$this->admin_model->add_product_price_level_query($new_product_price_level_data);
									'<br>';
									$new_product_price_level_data = array(
										'id' => '',
										'product_id' => $product_id,
										'price_level_id' => 7,
										'price' => $priceF17,
										'status' => 1
									);
									$this->admin_model->add_product_price_level_query($new_product_price_level_data);
									'<br>';
									$new_product_price_level_data = array(
										'id' => '',
										'product_id' => $product_id,
										'price_level_id' => 8,
										'price' => $priceF15,
										'status' => 1
									);
									$this->admin_model->add_product_price_level_query($new_product_price_level_data);
									'<br>';
									$new_product_price_level_data = array(
										'id' => '',
										'product_id' => $product_id,
										'price_level_id' => 9,
										'price' => $price0,
										'status' => 1
									);
									$this->admin_model->add_product_price_level_query($new_product_price_level_data);
									'<br>';
									$new_product_price_level_data = array(
										'id' => '',
										'product_id' => $product_id,
										'price_level_id' => 10,
										'price' => $priceTJ,
										'status' => 1
									);
									$this->admin_model->add_product_price_level_query($new_product_price_level_data)
								*/
								.'</td>
							</tr>';
						}

						
				}
				$html .=
				'</tbody></table>';


				// echo "<pre>";
				// 	echo "<code>";
				// 		print_r($sheetData);
				// 	echo "</code>";
				// echo "</pre>";

				$htmlstr = str_replace(array("\r", "\n"), '', $html);
				$data['htmldata'] = $htmlstr;
				echo json_encode($data);
				exit;

			} else {
				$type = "error";
				$message = "Invalid File Type. Upload Excel File.";
			}
		}
		else{
			//echo  'saveconfirm: '.$_POST['saveconfirm'];
		}


		exit();
    }

	public function bulk_add_to_cart(){
		extract($_POST);
		$cart_data = $_POST['data'];
		$data['level'] = $this->session->userdata('level');

		$cart_item_array = Array();
		$sessiontemp = Array();
		if($data['level'] <= 1){
			$price_level = 'level1price';
		}
		else if($data['level'] == 2){
			$price_level = 'level2price';
		}
		else{
			$price_level = 'ex';
		}

		//unset($_SESSION['cart_data']);

		if(!isset($_SESSION['cart_data'])){
			$_SESSION['cart_data'] = Array();
		}
		//echo '<br>bulk_add_to_cart<br>';
		
		if(isset($_SESSION['cart_data']) && !empty($_SESSION['cart_data'])){  // check if items in session already exists
			
			//echo 'session found<br>';

			$sessiontemp = $_SESSION['cart_data'];

			foreach($cart_data as $cart_item){ // loop through each incoming item

				$item_key = array_search($cart_item['masterid'], array_column($sessiontemp, 'masterid')); // $key = 2;
				//echo '<br>cart_item[masterid]: '.$cart_item['masterid'].' - key: '.$item_key.'--';

				if ($item_key !== false)
				{
					//echo 'update';
					foreach($sessiontemp as &$session_item){
						if($session_item['masterid'] == $cart_item['masterid'] && $session_item['qty'] != $cart_item['qty']){
							//echo '<br><br>qty diff found in mutual masterid:' . $cart_item['masterid'] . '-' . $session_item['masterid'];
							//echo '<br>cart_item qty: ' . $cart_item['qty'] . ' - session qty: ' . $session_item['qty'];
							//echo '<br>';
							$session_item['qty'] = $cart_item['qty'];
						}
					}
				}
				else{
					//echo 'add';
					$product_data = $this->page_model->get_product_by_master_id_query($cart_item['masterid']);

					if($price_level == 'ex'){
						$create_cart_item = [
							'id' => $product_data[0]->id,
							'item_id' => $cart_item['item'],
							'masterid' => $cart_item['masterid'],
							'description' => $product_data[0]->description,
							'qty' => $cart_item['qty'],
						];
						
						$product_price_level_data['product_id'] = $create_cart_item['id'];
						$product_price_level_data['price_level_id'] = $data['level'];
	
						$product_price_level = $this->page_model->get_product_price_level_by_productid_pricelevel($product_price_level_data);
						if($product_price_level){
							$create_cart_item['price'] = $product_price_level[0]->price;
						}
					}
					else{
						$create_cart_item = [
							'id' => $product_data[0]->id,
							'item_id' => $cart_item['item'],
							'masterid' => $cart_item['masterid'],
							'description' => $product_data[0]->description,
							'qty' => $cart_item['qty'],
							'price' => $product_data[0]->$price_level,
						];
					}

					//array_push($cart_item_array, $create_cart_item);
					array_push($sessiontemp, $create_cart_item);
				}

			}
			//echo '<br><br>';
			//var_dump($sessiontemp);

			//array_push($cart_item_array, $create_cart_item);
			unset($_SESSION['cart_data']);
			$_SESSION['cart_data'] = Array();

			$_SESSION['cart_data'] = $sessiontemp;
			$cart_item_array = $sessiontemp;

		}
		else{

			//echo 'session cart_data empty<br>';

			foreach($cart_data as $cart_item){ // loop through each incoming item
				$product_data = $this->page_model->get_product_by_master_id_query($cart_item['masterid']);

				if($price_level == 'ex'){
					$create_cart_item = [
						'id' => $product_data[0]->id,
						'item_id' => $cart_item['item'],
						'masterid' => $cart_item['masterid'],
						'description' => $product_data[0]->description,
						'qty' => $cart_item['qty'],
					];
					
					$product_price_level_data['product_id'] = $create_cart_item['id'];
					$product_price_level_data['price_level_id'] = $data['level'];

					$product_price_level = $this->page_model->get_product_price_level_by_productid_pricelevel($product_price_level_data);
					if($product_price_level){
						$create_cart_item['price'] = $product_price_level[0]->price;
					}
				}
				else{
					$create_cart_item = [
						'id' => $product_data[0]->id,
						'item_id' => $cart_item['item'],
						'masterid' => $cart_item['masterid'],
						'description' => $product_data[0]->description,
						'qty' => $cart_item['qty'],
						'price' => $product_data[0]->$price_level,
					];
				}



				array_push($cart_item_array, $create_cart_item);
				array_push($_SESSION['cart_data'], $create_cart_item);
			}

		}

		//var_dump($_SESSION['cart_data']);
		echo json_encode($cart_item_array);
		exit;
	}

	public function empty_cart(){
		unset($_SESSION['cart_data']);
		exit;
	}

	public function order_submission(){

		if(isset($this->session->username)) {
			// echo 'order_submission';
			//var_dump($this->input->post());
			//echo '<br><br>';
			$countpost = count($this->input->post()) - 2;
			$total_products = round($countpost / 5);
			//echo '<br>total products: '.$total_product;

			$total_price = 0;
			$total_qty  = 0;
			$product_data = '';
			$product_data_array =  new ArrayObject();

			for($i=1;$i<=$total_products;$i++){

				$product_id = $this->input->post('product-id-'.$i);
				$product_name = $this->input->post('itemname-'.$i);
				$product_qty = $this->input->post('itemqty-'.$i);
				$product_price = $this->input->post('itemunitcost-'.$i);
				$product_total = $this->input->post('itemlinecost-'.$i);

				if($product_id != null){
					$total_price = $total_price + $product_total;
					$total_qty = $total_qty + $product_qty;
					
					$product_data_array[$i]['product_id'] = $product_id;
					$product_data_array[$i]['product_qty'] = $product_qty;
					$product_data_array[$i]['product_price'] = $product_price;
					$product_data_array[$i]['product_total'] = $product_total;
				}


			}

			// echo '<br><br>';
			// echo 'product_data: '.$product_data;
			// echo '<br>';
			// echo 'total_products: '.$total_products;
			// echo '<br>';
			// echo 'total_price: '.$total_price;
			// echo '<br>';
			// echo 'total_qty: '.$total_qty;
			// echo '<br>';
			// echo 'total_qty: '.$total_qty;
			// echo '<br>';
	
			function generateRandomString($length = 10) {
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				return $randomString;
			}
			
			//echo '<br>';
			//echo generateRandomString();
			//echo '<br>';

			$last_order_id = $this->order_model->get_last_order_id();
			$last_order_id = $last_order_id + 1;
			$new_order_id_wo_pno = sprintf("%06d", $last_order_id);
			$new_order_id = 'PNO'.$new_order_id_wo_pno;

			//echo '$new_order_id: '.$new_order_id ;
			//$new_order_id = generateRandomString();
			//echo '<pre>'.var_dump($product_data_array).'</pre>';

			$addData = array(
				'id' => '',
				'order_id' => $new_order_id,
				'order_data' => json_encode($product_data_array),
				'total_products' => $total_products,
				'order_total_qty' => $total_qty,
				'order_total_price' => $total_price,
				'user_id' => $this->session->uid,
				'order_level' => $this->session->userdata('level') == 0 ? 1 : $this->session->userdata('level'),
				'po_number' => $this->input->post('po_number'),
				'notes' => $this->input->post('order_notes'),
				'datetime' => time(),
				'status' => 0,
			);

			//echo '<pre><code>'.json_encode($addData).'</code></pre>';
			//echo '<pre>'.var_dump($addData).'</pre>';

			/*  insert order to DB */
			$data['response'] = $this->order_model->insert_order($addData);

			//echo '$data[response]: '.$data['response'];
			//$data['response'] = 1;

			if($data['response'] == 1){

				/*  send order confirmation via email */
				$emailhtml = '<table width="650px" align="center">';
				$emailhtml .= '<tr><td>';

				$emailhtml .= "<a style='text-align: center;width: 100%;display: inline-block;' href='https://order.premiumnut.com/home'>
				<img src='https://order.premiumnut.com/assets/images/PremiumNutHeader2018.png' alt='PremiumNut' style='max-height:70px;'>
		  		</a>";
				$emailhtml .= "<p style='text-align: center;' >New Order # ".$new_order_id." has been placed at Premiumnut Order App.<br> Following are the details:</p><br>";
				foreach($addData as $value => $key){

					/*  order data table formatting */
					if($value == 'order_data'){
						$emailhtml .= "<h3 style='text-align:center;'>Order Data</h3>";
						$order_items = json_decode($key);
						$emailhtml .= '<table border="1px" cellpadding="0" cellspacing="0">';
						$emailhtml .= '<thead>';
							$emailhtml .= '<th>Sr.</th>';
							$emailhtml .= '<th>Master ID</th>';
							$emailhtml .= '<th>Name</th>';
							$emailhtml .= '<th>Quantity</th>';
							$emailhtml .= '<th>Price (per Unit)</th>';
							$emailhtml .= '<th>Total</th>';
						$emailhtml .= '</thead>';
						foreach($order_items as $key => $item){
							$product_data = $this->page_model->get_product_by_product_id($item->product_id);
							//print_r($product_data);
							$emailhtml .= '<tr>';
								$emailhtml .= '<td>'.$key.'</td>';
								$emailhtml .= '<td>'.$product_data[0]->master_id.'</td>';
								$emailhtml .= '<td>'.$product_data[0]->description.'</td>';
								$emailhtml .= '<td>'.$item->product_qty.'</td>';
								$emailhtml .= '<td>'.$item->product_price.'</td>';
								$emailhtml .= '<td>'.$item->product_total.'</td>';
							$emailhtml .= '</tr>';
						}
						$emailhtml .= '</table><br>';

					}
					elseif($value == 'order_level' || $value == 'id'){
						$emailhtml .= "";
					}
					elseif($value == 'datetime'){
						$emailhtml .= "<p><strong>$value:</strong>".date( 'd/m/Y', $key)."</p>";
					}
					elseif($value == 'user_id'){
						$user_info = $this->customer_model->get_user_data($key);
						$emailhtml .= '<p><strong>Customer Name:</strong> '.$user_info[0]['company'].'</p>';
					}
					else{
						$emailhtml .= "<p><strong>$value:</strong> $key</p>";
					}
				}

				$emailhtml .= '</td></tr>';
				$emailhtml .= '</table>';


				$this->email->from('no-reply@premiumnut.com', 'Premiumnut Order');

				$this->email->to('premiumordering@gmail.com');
				$this->email->bcc('admin@premiumnut.com');
				$this->email->bcc('maverik_90@hotmail.com');
				$this->email->bcc('orders@premiumnut.com');

				$this->email->subject('New Order # '.$new_order_id.' has been placed by User: '.$this->session->userdata('username').' on Premium Nut Order App');
				$this->email->message($emailhtml);
				
				//print_r($emailhtml);

				$this->email->send();
				unset($_SESSION['cart_data']);
				redirect('order-success?order_id='.$new_order_id);

			}
			else{
				//redirect('order-falied?order_id='.$new_order_id);
			}

		}

	}


	public function export_order_list_quickbooks(){
		echo '<br>export_order_list_quickbooks';
		$single_order_id = $_GET['order_id'];

		$books = [
			[
				'Customer',
				'Transaction Date', 
				'RefNumber',
				'PO Number',
				'Terms',
				'Class',
				'Template Name',
				'To Be Printed',
				'Ship Date',
				'BillTo Line1',
				'BillTo Line2',
				'BillTo Line3',
				'BillTo Line4',
				'BillTo City',
				'BillTo State',
				'BillTo PostalCode',
				'BillTo Country',
				'ShipTo Line1',
				'ShipTo Line2',
				'ShipTo Line3',
				'ShipTo Line4',
				'ShipTo City',
				'ShipTo State',
				'ShipTo PostalCode',
				'ShipTo Country',
				'Phone',
				'Fax',
				'Email',
				'Contact Name',
				'First Name',
				'Last Name',
				'Rep',
				'Due Date',
				'Ship Method',
				'Customer Message',
				'Memo',
				'Cust. Tax Code',
				'Item',
				'Quantity',
				'Description',
				'Price',
				'Is Pending',
				'Item Line Class',
				'Service Date',
				'FOB',
				'Customer Acct No',
				'Sales Tax Item',
				'To Be E-Mailed',
				'Other',
				'Other1',
				'Other2',
				'Unit of Measure',
				'AR Account',
				'Currency',
				'Exchange Rate',
				'Sales Tax Code'
			]
		];


		$this->load->library('SimpleXLSXGen/SimpleXLSXGen');

		if( $this->session->userdata('level') <= '0' ){ // for admins

			$data['orders_list'] = $this->admin_model->get_all_orders();

			if(isset($single_order_id) && $single_order_id != ''){
				$data['orders_list'] = $this->admin_model->get_single_order($single_order_id);
				$order_all = 'single';
			}
			else{
				$data['orders_list'] = $this->admin_model->get_all_orders();
				$order_all = 'all';
			}

			foreach($data['orders_list'] as $order_data){
				//echo '<br>';

				//echo '<br>id: '.$order_data['id'].'-';
				//echo '<br>user_id: '.$order_data['user_id'].'-';
				//echo 'Company: '.$user_info[0]['company'];
				//echo 'Transaction: '.date( 'd/m/Y' ,$order_data['datetime']);
				//echo 'RefNumber: '.$order_data['order_id'];


				$user_info = $this->admin_model->get_user_data($order_data['user_id']);
				///print_r($user_info);
				$total_products_count = $order_data['total_products'];
				$order_product_data = json_decode($order_data['order_data']);
				foreach($order_product_data as $order_product){
					//var_dump($order_product);	
					$order_product_description = $this->page_model->get_product_description_by_id($order_product->product_id);
					$order_product_master_id = $this->page_model->get_product_masterid_by_id($order_product->product_id);
					$order_product_id = $order_product->product_id;
					$order_product_qty = $order_product->product_qty;
					$order_product_price = $order_product->product_price;

					$product_data = $this->order_model->get_product_info_by_id($order_product->product_id);

					$books[] = (object) [
						'Customer' => $user_info[0]['company'],
						'Transaction Date' => date( 'd/m/Y' ,$order_data['datetime']),
						'RefNumber' => $order_data['order_id'],
						'PO Number' => $order_data['po_number'],
						'Terms' => '',
						'Class' => '',
						'Template Name' => '',
						'To Be Printed' => 'Y',
						'Ship Date' => date( 'd/m/Y' ,$order_data['datetime']),
						'BillTo Line1' => $user_info[0]['company'],
						'BillTo Line2' => $user_info[0]['address'],
						'BillTo Line3' => '',
						'BillTo Line4' => '',
						'BillTo City' => $user_info[0]['city'],
						'BillTo State' => '',
						'BillTo PostalCode' => $user_info[0]['postcode'],
						'BillTo Country' => $user_info[0]['country'],
						'ShipTo Line1' => $user_info[0]['company'],
						'ShipTo Line2' => $user_info[0]['address'],
						'ShipTo Line3' => '',
						'ShipTo Line4' => '',
						'ShipTo City' => $user_info[0]['city'],
						'ShipTo State' => '',
						'ShipTo PostalCode' => $user_info[0]['postcode'],
						'ShipTo Country' => $user_info[0]['country'],
						'Phone' => $user_info[0]['phone'],
						'Fax' => '',
						'Email' => $user_info[0]['email'],
						'Contact Name' => $user_info[0]['first_name'].' '.$user_info[0]['last_name'],
						'First Name' => $user_info[0]['first_name'],
						'Last Name' => $user_info[0]['last_name'],
						'Rep' => '',
						'Due Date' => '',
						'Ship Method' => '',
						'Customer Message' => '',
						'Memo' => $order_data['notes'],
						'Cust. Tax Code' => 'Non',
						'Item' => $product_data['item_name'],
						'Quantity' => $order_product_qty,
						'Description' => $order_product_description,
						'Price' => $order_product_price,
						'Is Pending' => '',
						'Item Line Class' => '',
						'Service Date' => '',
						'FOB' => '',
						'Customer Acct No' => '',
						'Sales Tax Item' => '',
						'To Be E-Mailed' => '',
						'Other' => '',
						'Other1' => '',
						'Other2' => '',
						'Unit of Measure' => '',
						'AR Account' => '',
						'Currency' => 'USD',
						'Exchange Rate' => 1,
						'Sales Tax Code' => 'Non',
					];
				}

			}
		}
		elseif($this->session->userdata('level') >= '0'){ // for  customers

			$data['orders_list'] = $this->customer_model->get_all_orders_by_user($data['id']);
			foreach($data['orders_list'] as $order_data){
				$user_info = $this->customer_model->get_user_data($order_data['user_id']);

				$total_products_count = $order_data['total_products'];
				
				$order_product_data = json_decode($order_data['order_data']);
				foreach($order_product_data as $order_product){
					//var_dump($order_product);	
					$order_product_description = $this->page_model->get_product_description_by_id($order_product->product_id);
					$order_product_master_id = $this->page_model->get_product_masterid_by_id($order_product->product_id);
					$order_product_id = $order_product->product_id;
					$order_product_qty = $order_product->product_qty;
					$order_product_price = $order_product->product_price;

					$books[] = (object) [
						'Customer' => $user_info[0]['company'],
						'Transaction Date' => date( 'd/m/Y' ,$order_data['datetime']),
						'RefNumber' => $order_data['order_id'],
						'PO Number' => $order_data['po_number'],
						'Terms' => '',
						'Class' => '',
						'Template Name' => '',
						'To Be Printed' => 'Y',
						'Ship Date' => date( 'd/m/Y' ,$order_data['datetime']),
						'BillTo Line1' => $user_info[0]['company'],
						'BillTo Line2' => $user_info[0]['address'],
						'BillTo Line3' => '',
						'BillTo Line4' => '',
						'BillTo City' => $user_info[0]['city'],
						'BillTo State' => '',
						'BillTo PostalCode' => $user_info[0]['postcode'],
						'BillTo Country' => $user_info[0]['country'],
						'ShipTo Line1' => $user_info[0]['company'],
						'ShipTo Line2' => $user_info[0]['address'],
						'ShipTo Line3' => '',
						'ShipTo Line4' => '',
						'ShipTo City' => $user_info[0]['city'],
						'ShipTo State' => '',
						'ShipTo PostalCode' => $user_info[0]['postcode'],
						'ShipTo Country' => $user_info[0]['country'],
						'Phone' => $user_info[0]['phone'],
						'Fax' => '',
						'Email' => $user_info[0]['email'],
						'Contact Name' => $user_info[0]['first_name'].' '.$user_info[0]['last_name'],
						'First Name' => $user_info[0]['first_name'],
						'Last Name' => $user_info[0]['last_name'],
						'Rep' => '',
						'Due Date' => '',
						'Ship Method' => '',
						'Customer Message' => '',
						'Memo' => $order_data['notes'],
						'Cust. Tax Code' => 'Non',
						'Item' => $product_data['item_name'],
						'Quantity' => $order_product_qty,
						'Description' => $order_product_description,
						'Price' => $order_product_price,
						'Is Pending' => '',
						'Item Line Class' => '',
						'Service Date' => '',
						'FOB' => '',
						'Customer Acct No' => '',
						'Sales Tax Item' => '',
						'To Be E-Mailed' => '',
						'Other' => '',
						'Other1' => '',
						'Other2' => '',
						'Unit of Measure' => '',
						'AR Account' => '',
						'Currency' => 'USD',
						'Exchange Rate' => 1,
						'Sales Tax Code' => 'Non',
					];


				}
			}
		}

		$xlsx = SimpleXLSXGen::fromArray( $books );
		
		if($order_all == 'all'){
			$user_filename = 'qbimport-'.$this->session->userdata('uid').'-'.$this->session->userdata('username').'-all';
		}else{
			$user_filename = 'qbimport-'.$this->session->userdata('uid').'-'.$this->session->userdata('username').'-'.$order_data['order_id'];
		}

		$xlsx->saveAs( 'assets/imports/'.$user_filename.'.xlsx'); 
		//$xlsx->download('assets/imports/'.$user_filename.'.xlsx');
		redirect('https://order.premiumnut.com/assets/imports/'.$user_filename.'.xlsx');



	}


}
?>