<?php

class form_Fn extends Fn{

	public function address( $data=array(), $options=null ) {

		if( $options===null )  $options = $data;

		$options = array_merge( array(
			'field_name' => 'address',
			'field_first_name' => '',
			'field_last_name' => '',
			'fields' => array()
		), $options );

		$name = !empty($options['field_name'])? $options['field_name']:'address';

		if( !empty($options['field_first_name']) ){
			$name = $options['field_first_name'].$name;
		}

		if( !empty($options['field_last_name']) ){
			$name .= $options['field_last_name'];
		}

		$city = array(
            'id' => 'address_city', 
            'name' => $name.'[city]', 
            'label' => 'จังหวัด',
            'value' => !empty($data['city']) ? $data['city']:''
        );
		if( !empty($options['city'])){
            if( is_array($options['city']) ){
            	$city['type'] = 'select';
            	$city['options'] = $options['city'];
            }
        }
        
        $fields = array( 0=> 
            array( 0 => 
                array(
                    'id' => 'address_street', 
                    'name' => $name.'[street]', 
                    'label' => 'Street',
                    'value' => !empty($data['street']) ? $data['street']:''
                ),
            ),

            array( 0 => 
                array(
                    'id' => 'address_city', 
                    'name' => $name.'[city]', 
                    'label' => 'City',
                    'value' => !empty($data['city']) ? $data['city']:''
                ),
                array(
                    'id' => 'address_district', 
                    'name' => $name.'[district]', 
                    'label' => 'State',
                    'value' => !empty($data['district']) ? $data['district']:''
                ),
                array(
                    'id' => 'address_amphur', 
                    'name' => $name.'[amphur]', 
                    'label' => 'Zip',
                    'value' => !empty($data['amphur']) ? $data['amphur']:''
                ),
            ),

            array( 0 => 
                array(
                    'id' => 'address_zip', 
                    'name' => $name.'[zip]', 
                    'label' => 'Country',
                    'value' => !empty($data['zip']) ? $data['zip']:''
                ),
            ),
        );

        return '<div class="table-address-wrap">'.  $this->__address( $fields ) .'</div>';
	}
	private function __address($data) {
		$str = '';
        foreach ($data as $rows) {

            $str .= '<table cellspacing="0" class="table-address"><tr>';
            // cell
            foreach ($rows as $cell => $value) {
                
                $type = isset($value['type']) ? $value['type'] : 'text';
                $id = isset($value['id']) ? $value['id'] : '';
                $name = isset($value['name']) ? $value['name'] : $id;
                $label = isset($value['label']) ? $value['label'] : '';
                
                if($type=='select'){
                    
                    $option = '<option value="">-</option>';
                    $val = isset($value['value']) ? $value['value'] : '';
                    foreach ($value['options'] as $data) {

                        $active = $val==$data['id'] ? ' selected="1"':'';
                        
                        $option .= '<option'.$active.' value="'.$data['id'].'">'.$data['name'].'</option>';
                    }

                    $input = '<select class="inputtext" id="'.$id.'" name="'.$name.'">'.$option.'</select>';
                }
                else{

                    $val = isset($value['value']) ? ' value="'.$value['value'].'"':'';
                    $input = '<input id="'.$id.'" autocomplete="off" class="inputtext" type="text" name="'.$name.'"'.$val.'>';
                }

                $str .= '<td class="label"><label for="'.$id.'">'.$label.'</label></td>';
                $str .= '<td class="data">'.$input.'</div>';

            }

            $str .= '</tr></table>';
        }

        return $str;
	} 

	public function fullname( $data=array(), $options=null ) {
	
		$options = array_merge( array(
			'field_first_name' => '',
			'field_last_name' => '',
			'fields' => array(),
			'prefix_name' => array()
		), $options);


		$fields = array_merge( array( 
			'prefix_name' => array('id'=>'prefix_name','label'=> Translate::Val('Prefix Name'),'type'=>'select', 'options'=>$this->_prefixName($options['prefix_name']), 'addClass'=>'input-prefix'), 
			'first_name'  => array('id'=>'first_name','label'=> Translate::Val('First Name') ), 
			'last_name'  => array('id'=>'last_name','label'=> Translate::Val('Last Name') ), 
			'nickname'  => array('id'=>'nickname','label'=> Translate::Val('Nickname') ,'addClass' => 'input-nickname')
		), $options['fields'] );

		$_fields = array();
		foreach ($fields as $key => $field) {

			if( isset($field['disabled']) || empty($field['id']) ) continue;

			if( !empty($options['field_first_name']) ){
				$field['id'] = $options['field_first_name'].$field['id'];
			}

			if( !empty($options['field_last_name']) ){
				$field['id'] .= $options['field_last_name'];
			}

			if( !empty($data[ $key ]) ){
				$field['value'] = $data[ $key ];
			}

			$_fields[] = $field;
		}

		return '<div class="u-table-wrap u-table-fullname">'. $this->uTableCell( $_fields) .'</div>';
	}
	private function _prefixName( $options=array() ) {
		
        $a['Mr.'] = array('id'=>'Mr.', 'name'=> Translate::Val('Mr.') );
        $a['Mrs.'] = array('id'=>'Mrs.', 'name'=> Translate::Val('Mrs.') );
        $a['Ms.'] = array('id'=>'Ms.', 'name'=> Translate::Val('Ms.') );

        return array_merge($a, $options);
	}

	public function birthday( $data=null, $options=array() ) {

		$options = array_merge( array(
			'field_first_name' => 'birthday',
			'field_last_name' => '',
			'end_year' => 18
		), $options);

		if( $data==null ) $data = $options;

		$days[] = array('id'=>'00', 'name'=> '--'.Translate::Val('Date').'--' );
		for ($i=1; $i <= 31; $i++) { 
			$d = $i < 10 ? "0{$i}":$i;
		    $days[] = array('id'=>$d, 'name'=> $i);
		}

		$fields[] = array( 
		    'id' => $options['field_first_name'] . '_date',
		    'name' => $options['field_first_name'] . '[date]', 
		    'label' => Translate::Val('Day'),
		    'type' => 'select',
		    'options' => $days,
		    'value' => !empty($data['birthday']) ? date('j', strtotime($data['birthday']) ):''
		);

		$months[] = array('id'=>'00', 'name'=> '--'.Translate::Val('Month').'--' );
		for ($i=1; $i <= 12; $i++) { 
			$m = $i < 10 ? "0{$i}":$i;
		    $months[] = array('id'=>$m, 'name'=> $this->q('time')->month( $i, 0, $this->lang->getCode() ));
		}
		$fields[] = array( 
		    'id' =>  $options['field_first_name'] . '_month',
		    'name' => $options['field_first_name'].'[month]', 
		    'label' => Translate::Val('Month'),
		    'type' => 'select',
		    'options' => $months,
		    'value' => !empty($data['birthday']) ? date('n', strtotime($data['birthday']) ):''
		);

		$years[] = array('id'=>'0000', 'name'=> '--'.Translate::Val('Year').'--');
		$y = date('Y') - $options['end_year'];
		$i = 1;
		do {
		    $years[] = array('id'=>$y, 'name'=>$y );
		    $y--;  $i++;
		} while ($i <= 70);

		$fields[] = array( 
		    'id' =>  $options['field_first_name'] . '_year', 
		    'name' => $options['field_first_name'].'[year]', 
		    'label' => Translate::Val('Year'),
		    'type' => 'select',
		    'options' => $years,
		    'value' => !empty($data['birthday']) ? date('Y', strtotime($data['birthday']) ):''
		);

		return '<div class="u-table-wrap u-table-birthday">' . $this->uTableCell($fields) .'</div>';
	}


	/*
		$type: email, phone, social
		data: 
	*/
	public function contacts($type, $data=array(), $options=array()) {
		
		$options = array_merge( array(
			'field_first_name' => '',
			'field_last_name' => '',
			'field_label' => '',
			'has_add' => true,
		), $options);

		$field_name = !empty($options['field_first_name'])? $options['field_first_name']: $type;

		if( !empty($options['field_last_name']) ){
			$field_name .= $options['field_last_name'];
		}

		$fieldset = '';
		foreach ($data as $key => $value) {
			$fieldset .= $this->_contacts( $type, $options, $field_name, $value['name'], $value['value'] );
		}

		if( empty($data) ){
			$fieldset .= $this->_contacts( $type, $options, $field_name );
		}

		return '<fieldset id="'. str_replace(array('[',']'), array('_', ''), $field_name).'_fieldset" class="form-field clearfix form-field-'.$type.'" data-plugins="formcontacts">'.
		    $fieldset.
		'</fieldset>';
	}
	public function _contact_label_email() {
		$labels = array();
		$labels[] = array('text'=> Translate::Val('Personal Email') );
		$labels[] = array('text'=> Translate::Val('Work Email') );
		$labels[] = array('text'=> Translate::Val('Other Email') );

		return $labels;
	}
	public function _contact_label_phone() {
		$labels = array();
		$labels[] = array('text'=> Translate::Val('Mobile Phone') );
		$labels[] = array('text'=> Translate::Val('Work Phone') );
		$labels[] = array('text'=> Translate::Val('Home Phone') );
		$labels[] = array('text'=> Translate::Val('Other phone') );
		return $labels;
	}
	public function _contact_label_social() {
		$labels = array();
		$labels = array();
		$labels[] = array('text'=> Translate::Val('Line ID') );
		$labels[] = array('text'=> Translate::Val('Facebook') );
		$labels[] = array('text'=> Translate::Val('Other') );
		return $labels;
	}
	public function _contacts($type, $options=array(), $name='', $label='', $value='' ) {
		
		$labelselect = '';
		foreach ($this->{"_contact_label_{$type}"}() as $val) {
			$active = $label == $val['text'] ? ' selected="1"':'';
        	$labelselect .='<option'.$active.' value="'.$val['text'].'">'.$val['text'].'</option>';
    	}

    	if( empty($name) ) $name = 'contacts['.$type.']';

    	$actions = !empty($options['has_add']) 
    		? '<div class="controls-actions">'.
    			'<a class="btn-add js-add-field"><i class="icon-plus"></i><span>เพิ่ม</span></a>'.
    			'<a class="btn-add js-remove-field remove"><i class="icon-remove"></i><span>ลบ</span></a>'.
    		  '</div>'
    		:'';

		return '<div class="control-group">'.
	        '<label class="control-label">'.
	            '<select name="'.$name.'[name][]" class="labelselect">'.$labelselect.'</select>'.
	        '</label>'.
	        '<div class="controls">'.
	            '<input class="inputtext js-input" autocomplete="off" type="text" name="'.$name.'[value][]" value="'.$value.'" />'.
	            '<div class="notification"></div>'.
	            $actions.
	        '</div>'.
	    '</div>';
	}


	/* -- radio Button Group */
	public function radioButtonGroup( $options=array(), $checked='', $name='' ) {

		if( empty($checked) && !empty($options[0]['value']) ){
			$checked = $options[0]['value'];
		}

		$li = '';
		foreach ($options as $key => $value) {

			$_checked = $checked==$value['value'] ? ' checked':'';
			$label = isset($value['label']) ? $value['label']:$value['value'];
			$cls = 'btn';
			if( !empty($_checked) ){
				$cls .= ' btn-blue active';
			}

			$li.='<div class="'.$cls.'"><label class="radio hidden_elem"><input'.$_checked.' type="radio" name="'.$name.'" value="'.$value['value'].'" autocomplete="off"></label><span>'.$label.'</span></div>';
		}

		return '<div class="group-btn" data-plugins="radioButtonGroup">'. $li. '</div>';
	}


	public function checkboxList( $data=array(), $options=array() ) {
		
		$options = array_merge( array(
			'checked' => '',
			'name' => '',
		), $options);
		$li = '';
		foreach ($data as $key => $value) {
			
			$checked = $options['checked']==$value['id'] ? ' checked':'';
			

			$cls = '';
			if( !empty($value['addClass']) ){
				$cls .= !empty($cls) ? ' ':'';
				$cls .= $value['addClass'];
			}

			$cls = !empty($cls) ? ' class="'.$cls.'"': '';
			$li.='<li'.$cls.'><label class="checkbox"><input'.$checked.' type="checkbox" name="'.$options['name'].'" value="'.$value['id'].'" autocomplete="off"><span class="mls">'.$value['name'].'</span></label></li>';
		}

		return '<ul class="ui-checkbox-list">'. $li. '</ul>';

	}




	public function table_flight($data)
	{
		return '<div class="table-flight-wrap"><table class="table-flight"><tbody>'.

            '<tr>'.
                '<td class="td-label">Departure Flight</td>'.

                '<td class="td-input">
                    <label class="label" for="flight_departure_number">Flight NO</label>
                    <input type="text" class="inputtext" id="flight_departure_number" placeholder="" autocomplete="off" name="ser_go_flight_code" value="'.( !empty($data['go_flight_code'])? $data['go_flight_code']: '' ).'">
                </td>'.
                '<td class="td-route">
                    <label class="label" for="flight_departure_route">เส้นทาง</label>'.
                    '<input type="text" class="inputtext" id="flight_departure_route" placeholder="" autocomplete="off" name="ser_go_route" value="'.( !empty($data['go_flight_code'])? $data['go_route']: '' ).'">'.
                '</td>'.
                '<td class="td-time">
                    <label class="label" for="flight_departure_time">เวลา</label>

                    <input type="text" class="inputtext" id="flight_departure_time" placeholder="" autocomplete="off" name="ser_go_time" value="'.( !empty($data['go_flight_code'])? $data['go_time']: '' ).'">
                </td>'.
            '</tr>'.


            '<tr>'.
                '<td class="td-label">Arrival Flight</td>'.

                '<td class="td-input">
                    <label class="label" for="flight_arrival_number">Flight NO</label>
                    <input type="text" class="inputtext" placeholder="" id="flight_arrival_number" autocomplete="off" name="ser_return_flight_code" value="'.( !empty($data['go_flight_code'])? $data['return_flight_code']: '' ).'">
                </td>'.
                '<td class="td-route">'.
                    '<label class="label" for="flight_arrival_route">เส้นทาง</label>'.

                    '<input type="text" class="inputtext" id="flight_arrival_route" placeholder="" autocomplete="off" name="ser_return_route" value="'.( !empty($data['go_flight_code'])? $data['return_route']: '' ).'">'.
                '</td>'.
                '<td class="td-time">
                    <label class="label" for="flight_arrival_time">เวลา</label>
                    <input type="text" class="inputtext" id="flight_arrival_time" placeholder="" autocomplete="off" name="ser_return_time" value="'.( !empty($data['go_flight_code'])? $data['return_time']: '' ).'">
                </td>'.

            '</tr>'.

        '</tbody></table>';
	}

	public function imageCover($options=array())
	{	
		
		if( !is_array($options) ){
			$options['name'] = $options;
		}

		$options = array_merge( array(
			'name' => 'file1',
			'accept' => 'image/jpeg,image/png',
			'width' => 230,
			'height' => 230,
		), $options);

		$attr = '';
		$cls = 'uiCoverImageContainer preview-image has-empty';
		if( isset($options['src']) ){
			// $cls .= ' has-image';

			// $attr = ' onload="PreviewImage.load(this, '. $options['src'] .')"';
		}
		else{
			// $cls .= ' has-empty';
		}

		return '<div data-plugin="input__image" data-options="'.Fn::stringify( $options ).'" class="'.$cls.'"'.$attr.' data-width="'.$options['width'].'" data-height="'.$options['height'].'" style="width:'.$options['width'].'px;height:'.$options['height'].'px"><div class="uiCoverImage_empty"><i class="icon-image"></i></div><div class="uiCoverImage_image" role="preview"></div><div class="uiCoverImage_action"><a data-action="trigger"><i class="icon-camera"></i></a><a data-action="remove"><i class="icon-remove"></i></a></div><div class="uiCoverImage_overlay"><input role="button" type="file" accept="'.$options['accept'].'" name="'.$options['name'].'"></div><div class="uiCoverImage_loaderspin"><div class="loader-spin-wrap"><div class="loader-spin"></div></div></div></div>';
	}
}