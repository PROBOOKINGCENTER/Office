<?php

$tr = "";
if( !empty($this->results['items']) ){ 

    $seq = 0;
    foreach ($this->results['items'] as $i => $item) { 

        $cls = $i%2 ? 'even' : "odd";

        $dropdown = array();
        $dropdown[] = array(
            'text' => Translate::Val('Delete'),
            'href' => URL.'location/del/city/'.$item['id'],
            'attr' => array('data-plugin'=>'lightbox'),
            // 'icon' => 'remove'
        );


        $option = '';
        foreach ($this->countryList as $key => $value) {
        	$seter = $value['id']==$item['country_id'] ? ' selected': '';

            if( empty($value['enabled']) && empty($seter) ) continue;
            // $disabled = $value==$item['country_id'] ? ' selected': '';


        	$option.='<option'.$seter.' value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        $option = '<select class="inputtext" data-action-update="select" name="city_country_id">'.$option.'</select>';
        
        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            '<td class="name">'. 

                $item['name']. 

                ( !empty($item['description']) ? '<div class="fam fcg">'.$item['description'].'</div>': '' ) .

            '</td>'.

            '<td class="status">'. $option  .'</td>'.

            '<td class="check"><label class="checkbox"><input data-action-update="checked" name="city_enabled"'. (!empty($item['enabled'])?' checked':'') .' type="checkbox" value="'.$item['id'].'"></label></td>'.

            '<td class="actions">'.
            	'<div class="group-btn">'.
                    '<a class="btn" data-plugin="lightbox" href="'.URL.'location/edit/city/'.$item['id'].'"><i class="icon-pencil"></i><span class="mls">'.Translate::Val('Edit').'</span></a>'.
                    '<a data-plugins="dropdown" class="btn" data-options="'.$this->fn->stringify( array(
                            'select' => $dropdown,
                            'settings' =>array(
                                'axisX'=> 'right',
                                // 'parentElem'=>'.setting-main'
                            )
                        ) ).'"><i class="icon-ellipsis-v"></i></a>'.
                '</div>'.
            '</td>'.
              
        '</tr>';
        
    }
}

$table = '<table class="settings-table"><tbody>'. $tr. '</tbody></table>';