<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Person extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('person_model','person');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('person_view');
	}

	public function ajax_list()
	{
		$list = $this->person->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $person) {
			$no++;
			$row = array();
			$row[] = $person->serviceID;
			$row[] = $person->noID;
			$row[] = $person->namaPlgn;
			$row[] = $person->order;
			$row[] = $person->status;
			$row[] = $person->accBill;
			$row[] = $person->paket;
			$row[] = $person->tgl_pesan;
			$row[] = $person->tgl_akhir;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$person->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$person->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->person->count_all(),
						"recordsFiltered" => $this->person->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->person->get_by_id($id);
		$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'serviceID' => $this->input->post('serviceID'),
				'noID' => $this->input->post('noID'),
				'namaPlgn' => $this->input->post('namaPlgn'),
				'order' => $this->input->post('order'),
				'status' => $this->input->post('status'),
				'accBill' => $this->input->post('accBill'),
				'paket' => $this->input->post('paket'),
				'tgl_pesan' => $this->input->post('tgl_pesan'),
				'tgl_akhir' => $this->input->post('tgl_akhir'),
			);
		$insert = $this->person->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'serviceID' => $this->input->post('serviceID'),
				'noID' => $this->input->post('noID'),
				'namaPlgn' => $this->input->post('namaPlgn'),
				'order' => $this->input->post('order'),
				'status' => $this->input->post('status'),
				'accBill' => $this->input->post('accBill'),
				'paket' => $this->input->post('paket'),
				'tgl_pesan' => $this->input->post('tgl_pesan'),
				'tgl_akhir' => $this->input->post('tgl_akhir'),
			);
		$this->person->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->person->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('serviceID') == '')
		{
			$data['inputerror'][] = 'serviceID';
			$data['error_string'][] = 'Service ID is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('noID') == '')
		{
			$data['inputerror'][] = 'noID';
			$data['error_string'][] = 'Nomer ID is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('namaPlgn') == '')
		{
			$data['inputerror'][] = 'namaPlgn';
			$data['error_string'][] = 'Nama Pelanggan is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('order') == '')
		{
			$data['inputerror'][] = 'order';
			$data['error_string'][] = 'Please select order';
			$data['status'] = FALSE;
		}

		if($this->input->post('status') == '')
		{
			$data['inputerror'][] = 'status';
			$data['error_string'][] = 'Please select status';
			$data['status'] = FALSE;
		}

		if($this->input->post('accBill') == '')
		{
			$data['inputerror'][] = 'accBill';
			$data['error_string'][] = 'accBill is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('paket') == '')
		{
			$data['inputerror'][] = 'paket';
			$data['error_string'][] = 'Please select paket';
			$data['status'] = FALSE;
		}

		if($this->input->post('tgl_pesan') == '')
		{
			$data['inputerror'][] = 'tgl_pesan';
			$data['error_string'][] = 'tgl_pesan is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('tgl_akhir') == '')
		{
			$data['inputerror'][] = 'tgl_akhir';
			$data['error_string'][] = 'tgl_akhir is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
