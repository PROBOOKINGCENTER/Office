<?php

require 'System/Airline.php';
require 'System/BookBank.php';
require 'System/ExtraLists.php';
require 'System/Activities.php';

class System_Model extends Model{

    public function __construct() {
        parent::__construct();

        $this->airline = new Airline();
        $this->bookbank = new BookBank();
        $this->extralists = new ExtraLists();
        $this->log = new Activities();
    }

    public function pageNav($me)
    {
        
        $nav = array(
            0=> array(

                // Overview
                  array('key'=>'dashboard', 'text'=> 'Dashboard', 'link'=>URL.'dashboard', 'icon'=>'area-chart')
                // , array('key'=>'calendar', 'text'=> Translate::Val('Calendar'), 'link'=>URL.'calendar', 'icon'=>'calendar-o')
                , array('key'=>'booking', 'text'=> Translate::Menu('จัดการการจองทัวร์'), 'link'=>URL.'booking', 'icon'=>'check-square-o')
                // , array('key'=>'manage_booking', 'text'=> Translate::Menu('รายละเอียดการจอง'), 'link'=>URL.'booking/manage', 'icon'=>'book')
                , array('key'=>'tour', 'text'=> Translate::Menu('ซีรี่ย์ทัวร์'), 'link'=>URL.'product/package', 'icon'=>'plane')
                // , array('key'=>'promotion', 'text'=> Translate::Val('Promotion'), 'link'=>URL.'promotion', 'icon'=>'tags')
            )
            , array(

                'Accounting'
                // Site
                // , array('key'=>'manage_ticket', 'text'=> Translate::Val('Titket Management'), 'link'=>URL.'manage/ticket', 'icon'=>'plane')
                , array('key'=>'accounting_payment', 'text'=> Translate::Menu('แจ้งการชำระเงิน'), 'link'=>URL.'accounting/payment', 'icon'=>'money')
                // , array('key'=>'accounting_invoice', 'text'=> Translate::Menu('Invoice'), 'link'=>URL.'accounting/invoice', 'icon'=>'file-text-o')
                // , array('key'=>'manage_cost', 'text'=> Translate::Val('Cost Summary'), 'link'=>URL.'manage/cost', 'icon'=>'money')

            )
            , array(

                'manage'
                // Site
                // , array('key'=>'manage_ticket', 'text'=> Translate::Val('Titket Management'), 'link'=>URL.'manage/ticket', 'icon'=>'plane')
                , array('key'=>'manage_tour', 'text'=> Translate::Val('จัดการทัวร์'), 'link'=>URL.'manage/tour', 'icon'=>'flag')
                , array('key'=>'promotion', 'text'=> Translate::Val('Promotion'), 'link'=>URL.'promotion', 'icon'=>'tags')
                // , array('key'=>'manage_cost', 'text'=> Translate::Val('Cost Summary'), 'link'=>URL.'manage/cost', 'icon'=>'money')

            )
            , array(
                'Support manage'
                // , array('key'=>'tasks', 'text'=> 'Tasks','link'=>URL.'tasks','icon'=>'check-square-o')
                // , array('key'=>'inbox', 'text'=> 'Mail','link'=>URL.'inbox', 'icon'=>'envelope-o')
                , array('key'=>'document', 'text'=> Translate::Menu('Document'), 'link'=>URL.'document', 'icon'=>'files-o')
                // , array('key'=>'tasks', 'text'=> 'Guide & TL','link'=>URL.'tasks','icon'=>'id-badge')
                // , array('key'=>'tasks', 'text'=> 'Incentive','link'=>URL.'incentive','icon'=>'podcast')
            )
            /*, array(

                'Sales'
                , array('key'=>'sales_due', 'text'=> Translate::Menu('Due Date Alert'), 'link'=>URL.'sales/due', 'icon'=>'calendar-check-o')
                , array('key'=>'sales_payment_daily', 'text'=> Translate::Menu('Daily Payment'), 'link'=>URL.'sales/payment/daily', 'icon'=>'podcast')
            )*/

            , array(

                'Agency'
                , array('key'=>'agency_sales', 'text'=> Translate::Menu('Agent'), 'link'=>URL.'agency/sales', 'icon'=>'user-circle-o')
                , array('key'=>'agency_company', 'text'=> Translate::Menu('Company Agency'), 'link'=>URL.'agency/company', 'icon'=>'address-card-o')

            )
            

            , array(
                'Manager'
                // , array('key'=>'tasks', 'text'=> 'Tasks','link'=>URL.'tasks','icon'=>'check-square-o')
                // , array('key'=>'inbox', 'text'=> 'Mail','link'=>URL.'inbox', 'icon'=>'envelope-o')
                // , array('key'=>'tasks', 'text'=> 'Guide & TL','link'=>URL.'tasks','icon'=>'id-badge')
                // , array('key'=>'tasks', 'text'=> 'Incentive','link'=>URL.'incentive','icon'=>'podcast')
                , array('key'=>'reports', 'text'=> 'Reports','link'=>URL.'reports','icon'=>'line-chart')
            )


            , array(
                'Customize'
                // , array('key'=>'site', 'text'=> 'Site Manager','link'=>URL.'site','icon'=>'object-ungroup')
                // , array('key'=>'business', 'text'=> 'Business Info','link'=>URL.'business','icon'=>'info-circle')
                // authorization
                , array('key'=>'authorization', 'text'=> 'Users', 'link'=>URL.'authorization','icon'=>'users' )
                // , array('key'=>'my','text'=> 'Account Settings','link'=>URL.'account/settings','icon'=>'user-circle')
                , array('key'=>'settings', 'text'=> 'Settings','link'=>URL.'settings','icon'=>'cogs')
            )
        );

        return $nav; 
    }

    public function navSettings($me){

        $url = URL.'settings/';

        $nav[] = array('text' => Translate::Menu('Airline'), 'key' => 'airline', 'link' => $url.'airline');
        $nav[] = array('text' => Translate::Menu('Book Bank'), 'key' => 'bookbank','link' => $url.'bookbank');
        $nav[] = array('text' => Translate::Menu('Extra Lists'), 'key' => 'extralists', 'link' => $url.'extralists');


        $sub = array();
        $sub[] = array('text' => Translate::Menu('Country'), 'key' => 'country', 'link' => $url.'location/country' );
        $nav[] = array('text' => Translate::Menu('Country'), 'key' => 'country', 'link' => $url.'location/country',/* 'sub'=>$sub*/ );
        $nav[] = array('text' => Translate::Menu('City'), 'key' => 'city', 'link' => $url.'location/city' );

        return $nav;
    }


    public function getPageOptions($opj, $action, $token)
    {   
        $items = array();
        if( $opj=='manage' && $action=='tabs' ){
            $items = array('dashboard', 'setting', 'period');
        }

        return$items;
    }


    /* -- Page Authorization -- */
    public function auth( $access=array() ) {

        // Settings
        $arr = array(
            // 'notifications' => array('view'=>1),
            // 'calendar' => array('view'=>1),

            'view_website' => array('view'=>1),
            'my' => array('view'=>1,'edit'=>1),
        );

        // Supper Admin
        if( in_array(1, $access) ){

            // Defult
            $arr['dashboard'] = array('view'=>1);
            $arr['overview'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['calendar'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['promotion'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);

            // manage
            $arr['manage_ticket'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['manage_tour'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['manage_promotion'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['manage_cost'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);


            // Sales
            $arr['tour'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['booking'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['manage_booking'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            
            $arr['sales_due'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['sales_serie'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['sales_payment_daily'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);

            // Accounting
            $arr['accounting_checkout'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['accounting_payment'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['accounting_invoice'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);


            // Agency
            $arr['agency_company'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['agency_sales'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);

            // Document
            $arr['document'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);

            // mail
            $arr['inbox'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);


            // Customize
            $arr['site'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['business'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['authorization'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['users'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['settings'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            

            $arr['tasks'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);
            $arr['reports'] = array('view'=>1);
            

            # Settings
            $arr['settings'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['settings_email'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['settings_airline'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['settings_bookbank'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['settings_extralists'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);

            // $arr['settings_location_region'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['settings_location_country'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            // $arr['settings_location_geography'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['settings_location_city'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            // $arr['settings_location_zone'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            // $arr['settings_location_district'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);

            $arr['settings_import'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['settings_export'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1); 
        }

        /* Manage */
        if( in_array(2, $access) ){

            $arr['dashboard'] = array('view'=>1);
            $arr['employees'] = array('view'=>1,'edit'=>1, 'del'=>1, 'add'=>1);

            $arr['orders'] = array('view'=>1);
            $arr['booking'] = array('view'=>1);

            $arr['package'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['promotions'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);

            
        }


        if( in_array(3, $access) ){
        }


        // PR
        if( in_array(4, $access) ){

            #People
            $arr['organization'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
            $arr['people'] = array('view'=>1,'edit'=>1,'del'=>1, 'add'=>1);
        }

        return $arr;
    }


    /* -- Page Default Data  -- */
    public function set($name, $value) {
        $sth = $this->db->prepare("SELECT option_name as name FROM system_info WHERE option_name=:name LIMIT 1");
        $sth->execute( array(
            ':name' => $name
        ) );

        if( $sth->rowCount()==1 ){
            $fdata = $sth->fetch( PDO::FETCH_ASSOC );

            if( !empty($value) ){
                $this->db->update('system_info', array(
                    'option_name' => $name,
                    'option_value' => $value
                ), "`option_name`='{$fdata['name']}'");
            }
            else{
                $this->db->delete('system_info', "`option_name`='{$fdata['name']}'");
            }
        }
        else{

            if( !empty($value) ){
                $this->db->insert('system_info', array(
                    'option_name' => $name,
                    'option_value' => $value
                ));
            }

        }
    }
    public function get() {
        $data = $this->db->select( "SELECT * FROM system_info" );

        $object = array();
        foreach ($data as $key => $value) {
            $object[$value['option_name']] = $value['option_value'];
        }

        if( empty($object['title']) ){
            $object['title'] = '';
        }

        return $object;
    }



    /* -- Prefix Name -- */
    public function prefixName( $options=array() ){

        $a['Mr.'] = array('id'=>'Mr.', 'name'=> Translate::Val('Mr.') );
        $a['Mrs.'] = array('id'=>'Mrs.', 'name'=> Translate::Val('Mrs.') );
        $a['Ms.'] = array('id'=>'Ms.', 'name'=> Translate::Val('Ms.') );

        return array_merge($a, $options);
    }
    public function getPrefixName($name) {

       $prefix = $this->prefixName();
       foreach ($prefix as $key => $value) {
            if( $value['id'] == $name ){
                $name = $value['name'];
                break;
            }
       }
       return $name;
    }


    public function booking_status()
    {
        $a = array();
        $a['0'] = array('id'=>'00', 'name'=>'จอง', 'type'=>'booking', 'color' => '#58ceb1');
        // $a['00'] = array('id'=>'00', 'name'=>'จอง', 'type'=>'booking', 'color' => '#58ceb1', 'disabled'=>1 );
        $a['05'] = array('id'=>'05', 'name'=>'W/L', 'type'=>'booking', 'color' => '');
        $a['10'] = array('id'=>'10', 'name'=>'แจ้ง Invoice', 'type'=>'booking', 'color' => '#f763eb');
        $a['20'] = array('id'=>'20', 'name'=>'DEP(PT)', 'desc'=>'ชำระเงินมัดจำไม่ครบ', 'type'=>'booking', 'color' => '#8bb8f1', 'is_payment'=>1);
        $a['25'] = array('id'=>'25', 'name'=>'DEP', 'desc'=>'ชำระเงินมัดจำครบ', 'type'=>'booking', 'color' => '#2f80e7', 'is_payment'=>1);
        $a['30'] = array('id'=>'30', 'name'=>'FP(PT)', 'desc'=>'ชำระเงินเต็มจำนวนไม่ครบ', 'type'=>'booking', 'color' => '#43d967', 'is_payment'=>1);
        $a['35'] = array('id'=>'35', 'name'=>'FP', 'desc'=>'ชำระเงินเต็มจำนวนครบ', 'type'=>'booking', 'color' => '#1e983b', 'is_payment'=>1);
        $a['40'] = array('id'=>'40', 'name'=>'CXL', 'desc'=>'ยกเลิกการจอง', 'type'=>'cancel', 'color' => '#ec2121');
        $a['50'] = array('id'=>'50', 'name'=>'จอง/WL', 'desc'=>'W/L', 'type'=>'waitlist', 'color' => '#564aa3');
        $a['55'] = array('id'=>'55', 'name'=>'แจ้งชำระเงิน', 'type'=>'booking', 'color' => '#ff902b');
        $a['60'] = array('id'=>'60', 'name'=>'ปฏิเสธการชำระเงิน', 'color' => '#f05050', 'type'=>'cancel');

        return $a;
    }
    public function booking_getStatus($id)
    {
        $status = $this->booking_status();
        return !empty($status[$id]) ? $status[$id]: array();
    }



    public function flagList()
    {
        $items = array();
        
        $items[] = array("id"=>"AF","name"=>"Afghanistan","category_id"=>2,"capital"=>"Kabul");
        $items[] = array("id"=>"AX","name"=>"Aland Islands","category_id"=>3,"capital"=>"Mariehamn");
        $items[] = array("id"=>"AL","name"=>"Albania","category_id"=>3,"capital"=>"Tirana");
        $items[] = array("id"=>"DZ","name"=>"Algeria","category_id"=>1,"capital"=>"Algiers");
        $items[] = array("id"=>"AS","name"=>"American Samoa","category_id"=>6,"capital"=>"Pago Pago");
        $items[] = array("id"=>"AD","name"=>"Andorra","category_id"=>3,"capital"=>"Andorra la Vella");
        $items[] = array("id"=>"AO","name"=>"Angola","category_id"=>1,"capital"=>"Luanda");
        $items[] = array("id"=>"AI","name"=>"Anguilla","category_id"=>4,"capital"=>"The Valley");
        $items[] = array("id"=>"AG","name"=>"Antigua and Barbuda","category_id"=>4,"capital"=>"St. John's");
        $items[] = array("id"=>"AR","name"=>"Argentina","category_id"=>5,"capital"=>"Buenos Aires");
        $items[] = array("id"=>"AM","name"=>"Armenia","category_id"=>2,"capital"=>"Yerevan");
        $items[] = array("id"=>"AW","name"=>"Aruba","category_id"=>5,"capital"=>"Oranjestad");
        $items[] = array("id"=>"AU","name"=>"Australia","category_id"=>6,"capital"=>"Canberra");
        $items[] = array("id"=>"AT","name"=>"Austria","category_id"=>3,"capital"=>"Vienna");
        $items[] = array("id"=>"AZ","name"=>"Azerbaijan","category_id"=>2,"capital"=>"Baku");
        $items[] = array("id"=>"BS","name"=>"Bahamas","category_id"=>4,"capital"=>"Nassau");
        $items[] = array("id"=>"BH","name"=>"Bahrain","category_id"=>2,"capital"=>"Manama");
        $items[] = array("id"=>"BD","name"=>"Bangladesh","category_id"=>2,"capital"=>"Dhaka");
        $items[] = array("id"=>"BB","name"=>"Barbados","category_id"=>4,"capital"=>"Bridgetown");
        $items[] = array("id"=>"BY","name"=>"Belarus","category_id"=>3,"capital"=>"Minsk");
        $items[] = array("id"=>"BE","name"=>"Belgium","category_id"=>3,"capital"=>"Brussels");
        $items[] = array("id"=>"BZ","name"=>"Belize","category_id"=>4,"capital"=>"Belmopan");
        $items[] = array("id"=>"BJ","name"=>"Benin","category_id"=>1,"capital"=>"Porto-Novo");
        $items[] = array("id"=>"BM","name"=>"Bermuda","category_id"=>4,"capital"=>"Hamilton");
        $items[] = array("id"=>"BT","name"=>"Bhutan","category_id"=>2,"capital"=>"Thimphu");
        $items[] = array("id"=>"BO","name"=>"Bolivia (Plurinational State of)","category_id"=>5,"capital"=>"Sucre");
        $items[] = array("id"=>"BQ","name"=>"Bonaire, Sint Eustatius and Saba","category_id"=>5,"capital"=>"Kralendijk");
        $items[] = array("id"=>"BA","name"=>"Bosnia and Herzegovina","category_id"=>3,"capital"=>"Sarajevo");
        $items[] = array("id"=>"BW","name"=>"Botswana","category_id"=>1,"capital"=>"Gaborone");
        $items[] = array("id"=>"BR","name"=>"Brazil","category_id"=>5,"capital"=>"Brasília");
        $items[] = array("id"=>"IO","name"=>"British Indian Ocean Territory","category_id"=>2,"capital"=>"Diego Garcia");
        $items[] = array("id"=>"BN","name"=>"Brunei Darussalam","category_id"=>2,"capital"=>"Bandar Seri Begawan");
        $items[] = array("id"=>"BG","name"=>"Bulgaria","category_id"=>3,"capital"=>"Sofia");
        $items[] = array("id"=>"BF","name"=>"Burkina Faso","category_id"=>1,"capital"=>"Ouagadougou");
        $items[] = array("id"=>"BI","name"=>"Burundi","category_id"=>1,"capital"=>"Bujumbura");
        $items[] = array("id"=>"CV","name"=>"Cabo Verde","category_id"=>1,"capital"=>"Praia");
        $items[] = array("id"=>"KH","name"=>"Cambodia","category_id"=>2,"capital"=>"Phnom Penh");
        $items[] = array("id"=>"CM","name"=>"Cameroon","category_id"=>1,"capital"=>"Yaoundé");
        $items[] = array("id"=>"CA","name"=>"Canada","category_id"=>4,"capital"=>"Ottawa");
        $items[] = array("id"=>"KY","name"=>"Cayman Islands","category_id"=>4,"capital"=>"George Town");
        $items[] = array("id"=>"CF","name"=>"Central African Republic","category_id"=>1,"capital"=>"Bangui");
        $items[] = array("id"=>"TD","name"=>"Chad","category_id"=>1,"capital"=>"N'Djamena");
        $items[] = array("id"=>"CL","name"=>"Chile","category_id"=>5,"capital"=>"Santiago");
        $items[] = array("id"=>"CN","name"=>"China","category_id"=>2,"capital"=>"Beijing");
        $items[] = array("id"=>"CX","name"=>"Christmas Island","category_id"=>2,"capital"=>"Flying Fish Cove");
        $items[] = array("id"=>"CC","name"=>"Cocos (Keeling) Islands","category_id"=>2,"capital"=>"West Island");
        $items[] = array("id"=>"CO","name"=>"Colombia","category_id"=>5,"capital"=>"Bogotá");
        $items[] = array("id"=>"KM","name"=>"Comoros","category_id"=>1,"capital"=>"Moroni");
        $items[] = array("id"=>"CK","name"=>"Cook Islands","category_id"=>6,"capital"=>"Avarua");
        $items[] = array("id"=>"CR","name"=>"Costa Rica","category_id"=>4,"capital"=>"San José");
        $items[] = array("id"=>"HR","name"=>"Croatia","category_id"=>3,"capital"=>"Zagreb");
        $items[] = array("id"=>"CU","name"=>"Cuba","category_id"=>4,"capital"=>"Havana");
        $items[] = array("id"=>"CW","name"=>"Curaçao","category_id"=>5,"capital"=>"Willemstad");
        $items[] = array("id"=>"CY","name"=>"Cyprus","category_id"=>3,"capital"=>"Nicosia");
        $items[] = array("id"=>"CZ","name"=>"Czech Republic","category_id"=>3,"capital"=>"Prague");
        $items[] = array("id"=>"CI","name"=>"Côte d'Ivoire","category_id"=>1,"capital"=>"Yamoussoukro");
        $items[] = array("id"=>"CD","name"=>"Democratic Republic of the Congo","category_id"=>1,"capital"=>"Kinshasa");
        $items[] = array("id"=>"DK","name"=>"Denmark","category_id"=>3,"capital"=>"Copenhagen");
        $items[] = array("id"=>"DJ","name"=>"Djibouti","category_id"=>1,"capital"=>"Djibouti");
        $items[] = array("id"=>"DM","name"=>"Dominica","category_id"=>4,"capital"=>"Roseau");
        $items[] = array("id"=>"DO","name"=>"Dominican Republic","category_id"=>4,"capital"=>"Santo Domingo");
        $items[] = array("id"=>"EC","name"=>"Ecuador","category_id"=>5,"capital"=>"Quito");
        $items[] = array("id"=>"EG","name"=>"Egypt","category_id"=>1,"capital"=>"Cairo");
        $items[] = array("id"=>"SV","name"=>"El Salvador","category_id"=>4,"capital"=>"San Salvador");
        $items[] = array("id"=>"GQ","name"=>"Equatorial Guinea","category_id"=>1,"capital"=>"Malabo");
        $items[] = array("id"=>"ER","name"=>"Eritrea","category_id"=>1,"capital"=>"Asmara");
        $items[] = array("id"=>"EE","name"=>"Estonia","category_id"=>3,"capital"=>"Tallinn");
        $items[] = array("id"=>"ET","name"=>"Ethiopia","category_id"=>1,"capital"=>"Addis Ababa");
        $items[] = array("id"=>"FK","name"=>"Falkland Islands","category_id"=>5,"capital"=>"Stanley");
        $items[] = array("id"=>"FO","name"=>"Faroe Islands","category_id"=>3,"capital"=>"Tórshavn");
        $items[] = array("id"=>"FM","name"=>"Federated States of Micronesia","category_id"=>6,"capital"=>"Palikir");
        $items[] = array("id"=>"FJ","name"=>"Fiji","category_id"=>6,"capital"=>"Suva");
        $items[] = array("id"=>"FI","name"=>"Finland","category_id"=>3,"capital"=>"Helsinki");
        $items[] = array("id"=>"MK","name"=>"Former Yugoslav Republic of Macedonia","category_id"=>3,"capital"=>"Skopje");
        $items[] = array("id"=>"FR","name"=>"France","category_id"=>3,"capital"=>"Paris");
        $items[] = array("id"=>"GF","name"=>"French Guiana","category_id"=>5,"capital"=>"Cayenne");
        $items[] = array("id"=>"PF","name"=>"French Polynesia","category_id"=>6,"capital"=>"Papeete");
        $items[] = array("id"=>"TF","name"=>"French Southern Territories","category_id"=>1,"capital"=>"Saint-Pierre, Réunion");
        $items[] = array("id"=>"GA","name"=>"Gabon","category_id"=>1,"capital"=>"Libreville");
        $items[] = array("id"=>"GM","name"=>"Gambia","category_id"=>1,"capital"=>"Banjul");
        $items[] = array("id"=>"GE","name"=>"Georgia","category_id"=>2,"capital"=>"Tbilisi");
        $items[] = array("id"=>"DE","name"=>"Germany","category_id"=>3,"capital"=>"Berlin");
        $items[] = array("id"=>"GH","name"=>"Ghana","category_id"=>1,"capital"=>"Accra");
        $items[] = array("id"=>"GI","name"=>"Gibraltar","category_id"=>3,"capital"=>"Gibraltar");
        $items[] = array("id"=>"GR","name"=>"Greece","category_id"=>3,"capital"=>"Athens");
        $items[] = array("id"=>"GL","name"=>"Greenland","category_id"=>4,"capital"=>"Nuuk");
        $items[] = array("id"=>"GD","name"=>"Grenada","category_id"=>4,"capital"=>"St. George's");
        $items[] = array("id"=>"GP","name"=>"Guadeloupe","category_id"=>4,"capital"=>"Basse-Terre");
        $items[] = array("id"=>"GU","name"=>"Guam","category_id"=>6,"capital"=>"Hagåtña");
        $items[] = array("id"=>"GT","name"=>"Guatemala","category_id"=>4,"capital"=>"Guatemala City");
        $items[] = array("id"=>"GG","name"=>"Guernsey","category_id"=>3,"capital"=>"Saint Peter Port");
        $items[] = array("id"=>"GN","name"=>"Guinea","category_id"=>1,"capital"=>"Conakry");
        $items[] = array("id"=>"GW","name"=>"Guinea-Bissau","category_id"=>1,"capital"=>"Bissau");
        $items[] = array("id"=>"GY","name"=>"Guyana","category_id"=>5,"capital"=>"Georgetown");
        $items[] = array("id"=>"HT","name"=>"Haiti","category_id"=>4,"capital"=>"Port-au-Prince");
        $items[] = array("id"=>"VA","name"=>"Holy See","category_id"=>3,"capital"=>"Vatican City");
        $items[] = array("id"=>"HN","name"=>"Honduras","category_id"=>4,"capital"=>"Tegucigalpa");
        $items[] = array("id"=>"HK","name"=>"Hong Kong","category_id"=>2,"capital"=>"Hong Kong");
        $items[] = array("id"=>"HU","name"=>"Hungary","category_id"=>3,"capital"=>"Budapest");
        $items[] = array("id"=>"IS","name"=>"Iceland","category_id"=>3,"capital"=>"Reykjavik");
        $items[] = array("id"=>"IN","name"=>"India","category_id"=>2,"capital"=>"New Delhi");
        $items[] = array("id"=>"ID","name"=>"Indonesia","category_id"=>2,"capital"=>"Jakarta");
        $items[] = array("id"=>"IR","name"=>"Iran (Islamic Republic of)","category_id"=>2,"capital"=>"Tehran");
        $items[] = array("id"=>"IQ","name"=>"Iraq","category_id"=>2,"capital"=>"Baghdad");
        $items[] = array("id"=>"IE","name"=>"Ireland","category_id"=>3,"capital"=>"Dublin");
        $items[] = array("id"=>"IM","name"=>"Isle of Man","category_id"=>3,"capital"=>"Douglas");
        $items[] = array("id"=>"IL","name"=>"Israel","category_id"=>2,"capital"=>"Jerusalem");
        $items[] = array("id"=>"IT","name"=>"Italy","category_id"=>3,"capital"=>"Rome");
        $items[] = array("id"=>"JM","name"=>"Jamaica","category_id"=>4,"capital"=>"Kingston");
        $items[] = array("id"=>"JP","name"=>"Japan","category_id"=>2,"capital"=>"Tokyo");
        $items[] = array("id"=>"JE","name"=>"Jersey","category_id"=>3,"capital"=>"Saint Helier");
        $items[] = array("id"=>"JO","name"=>"Jordan","category_id"=>2,"capital"=>"Amman");
        $items[] = array("id"=>"KZ","name"=>"Kazakhstan","category_id"=>2,"capital"=>"Astana");
        $items[] = array("id"=>"KE","name"=>"Kenya","category_id"=>1,"capital"=>"Nairobi");
        $items[] = array("id"=>"KI","name"=>"Kiribati","category_id"=>6,"capital"=>"South Tarawa");
        $items[] = array("id"=>"KW","name"=>"Kuwait","category_id"=>2,"capital"=>"Kuwait City");
        $items[] = array("id"=>"KG","name"=>"Kyrgyzstan","category_id"=>2,"capital"=>"Bishkek");
        $items[] = array("id"=>"LA","name"=>"Laos","category_id"=>2,"capital"=>"Vientiane");
        $items[] = array("id"=>"LV","name"=>"Latvia","category_id"=>3,"capital"=>"Riga");
        $items[] = array("id"=>"LB","name"=>"Lebanon","category_id"=>2,"capital"=>"Beirut");
        $items[] = array("id"=>"LS","name"=>"Lesotho","category_id"=>1,"capital"=>"Maseru");
        $items[] = array("id"=>"LR","name"=>"Liberia","category_id"=>1,"capital"=>"Monrovia");
        $items[] = array("id"=>"LY","name"=>"Libya","category_id"=>1,"capital"=>"Tripoli");
        $items[] = array("id"=>"LI","name"=>"Liechtenstein","category_id"=>3,"capital"=>"Vaduz");
        $items[] = array("id"=>"LT","name"=>"Lithuania","category_id"=>3,"capital"=>"Vilnius");
        $items[] = array("id"=>"LU","name"=>"Luxembourg","category_id"=>3,"capital"=>"Luxembourg City");
        $items[] = array("id"=>"MO","name"=>"Macau","category_id"=>2,"capital"=>"Macau");
        $items[] = array("id"=>"MG","name"=>"Madagascar","category_id"=>1,"capital"=>"Antananarivo");
        $items[] = array("id"=>"MW","name"=>"Malawi","category_id"=>1,"capital"=>"Lilongwe");
        $items[] = array("id"=>"MY","name"=>"Malaysia","category_id"=>2,"capital"=>"Kuala Lumpur");
        $items[] = array("id"=>"MV","name"=>"Maldives","category_id"=>2,"capital"=>"Malé");
        $items[] = array("id"=>"ML","name"=>"Mali","category_id"=>1,"capital"=>"Bamako");
        $items[] = array("id"=>"MT","name"=>"Malta","category_id"=>3,"capital"=>"Valletta");
        $items[] = array("id"=>"MH","name"=>"Marshall Islands","category_id"=>6,"capital"=>"Majuro");
        $items[] = array("id"=>"MQ","name"=>"Martinique","category_id"=>4,"capital"=>"Fort-de-France");
        $items[] = array("id"=>"MR","name"=>"Mauritania","category_id"=>1,"capital"=>"Nouakchott");
        $items[] = array("id"=>"MU","name"=>"Mauritius","category_id"=>1,"capital"=>"Port Louis");
        $items[] = array("id"=>"YT","name"=>"Mayotte","category_id"=>1,"capital"=>"Mamoudzou");
        $items[] = array("id"=>"MX","name"=>"Mexico","category_id"=>4,"capital"=>"Mexico City");
        $items[] = array("id"=>"MD","name"=>"Moldova","category_id"=>3,"capital"=>"Chișinău");
        $items[] = array("id"=>"MC","name"=>"Monaco","category_id"=>3,"capital"=>"Monaco");
        $items[] = array("id"=>"MN","name"=>"Mongolia","category_id"=>2,"capital"=>"Ulaanbaatar");
        $items[] = array("id"=>"ME","name"=>"Montenegro","category_id"=>3,"capital"=>"Podgorica");
        $items[] = array("id"=>"MS","name"=>"Montserrat","category_id"=>4,"capital"=>"Little Bay, Brades, Plymouth");
        $items[] = array("id"=>"MA","name"=>"Morocco","category_id"=>1,"capital"=>"Rabat");
        $items[] = array("id"=>"MZ","name"=>"Mozambique","category_id"=>1,"capital"=>"Maputo");
        $items[] = array("id"=>"MM","name"=>"Myanmar","category_id"=>2,"capital"=>"Naypyidaw");
        $items[] = array("id"=>"NA","name"=>"Namibia","category_id"=>1,"capital"=>"Windhoek");
        $items[] = array("id"=>"NR","name"=>"Nauru","category_id"=>6,"capital"=>"Yaren District");
        $items[] = array("id"=>"NP","name"=>"Nepal","category_id"=>2,"capital"=>"Kathmandu");
        $items[] = array("id"=>"NL","name"=>"Netherlands","category_id"=>3,"capital"=>"Amsterdam");
        $items[] = array("id"=>"NC","name"=>"New Caledonia","category_id"=>6,"capital"=>"Nouméa");
        $items[] = array("id"=>"NZ","name"=>"New Zealand","category_id"=>6,"capital"=>"Wellington");
        $items[] = array("id"=>"NI","name"=>"Nicaragua","category_id"=>4,"capital"=>"Managua");
        $items[] = array("id"=>"NE","name"=>"Niger","category_id"=>1,"capital"=>"Niamey");
        $items[] = array("id"=>"NG","name"=>"Nigeria","category_id"=>1,"capital"=>"Abuja");
        $items[] = array("id"=>"NU","name"=>"Niue","category_id"=>6,"capital"=>"Alofi");
        $items[] = array("id"=>"NF","name"=>"Norfolk Island","category_id"=>6,"capital"=>"Kingston");
        $items[] = array("id"=>"KP","name"=>"North Korea","category_id"=>2,"capital"=>"Pyongyang");
        $items[] = array("id"=>"MP","name"=>"Northern Mariana Islands","category_id"=>6,"capital"=>"Capitol Hill");
        $items[] = array("id"=>"NO","name"=>"Norway","category_id"=>3,"capital"=>"Oslo");
        $items[] = array("id"=>"OM","name"=>"Oman","category_id"=>2,"capital"=>"Muscat");
        $items[] = array("id"=>"PK","name"=>"Pakistan","category_id"=>2,"capital"=>"Islamabad");
        $items[] = array("id"=>"PW","name"=>"Palau","category_id"=>6,"capital"=>"Ngerulmud");
        $items[] = array("id"=>"PA","name"=>"Panama","category_id"=>4,"capital"=>"Panama City");
        $items[] = array("id"=>"PG","name"=>"Papua New Guinea","category_id"=>6,"capital"=>"Port Moresby");
        $items[] = array("id"=>"PY","name"=>"Paraguay","category_id"=>5,"capital"=>"Asunción");
        $items[] = array("id"=>"PE","name"=>"Peru","category_id"=>5,"capital"=>"Lima");
        $items[] = array("id"=>"PH","name"=>"Philippines","category_id"=>2,"capital"=>"Manila");
        $items[] = array("id"=>"PN","name"=>"Pitcairn","category_id"=>6,"capital"=>"Adamstown");
        $items[] = array("id"=>"PL","name"=>"Poland","category_id"=>3,"capital"=>"Warsaw");
        $items[] = array("id"=>"PT","name"=>"Portugal","category_id"=>3,"capital"=>"Lisbon");
        $items[] = array("id"=>"PR","name"=>"Puerto Rico","category_id"=>4,"capital"=>"San Juan");
        $items[] = array("id"=>"QA","name"=>"Qatar","category_id"=>2,"capital"=>"Doha");
        $items[] = array("id"=>"CG","name"=>"Republic of the Congo","category_id"=>1,"capital"=>"Brazzaville");
        $items[] = array("id"=>"RO","name"=>"Romania","category_id"=>3,"capital"=>"Bucharest");
        $items[] = array("id"=>"RU","name"=>"Russia","category_id"=>3,"capital"=>"Moscow");
        $items[] = array("id"=>"RW","name"=>"Rwanda","category_id"=>1,"capital"=>"Kigali");
        $items[] = array("id"=>"RE","name"=>"Réunion","category_id"=>1,"capital"=>"Saint-Denis");
        $items[] = array("id"=>"BL","name"=>"Saint Barthélemy","category_id"=>4,"capital"=>"Gustavia");
        $items[] = array("id"=>"SH","name"=>"Saint Helena, Ascension and Tristan da Cunha","category_id"=>1,"capital"=>"Jamestown");
        $items[] = array("id"=>"KN","name"=>"Saint Kitts and Nevis","category_id"=>4,"capital"=>"Basseterre");
        $items[] = array("id"=>"LC","name"=>"Saint Lucia","category_id"=>4,"capital"=>"Castries");
        $items[] = array("id"=>"MF","name"=>"Saint Martin","category_id"=>4,"capital"=>"Marigot");
        $items[] = array("id"=>"PM","name"=>"Saint Pierre and Miquelon","category_id"=>4,"capital"=>"Saint-Pierre");
        $items[] = array("id"=>"VC","name"=>"Saint Vincent and the Grenadines","category_id"=>4,"capital"=>"Kingstown");
        $items[] = array("id"=>"WS","name"=>"Samoa","category_id"=>6,"capital"=>"Apia");
        $items[] = array("id"=>"SM","name"=>"San Marino","category_id"=>3,"capital"=>"San Marino");
        $items[] = array("id"=>"ST","name"=>"Sao Tome and Principe","category_id"=>1,"capital"=>"São Tomé");
        $items[] = array("id"=>"SA","name"=>"Saudi Arabia","category_id"=>2,"capital"=>"Riyadh");
        $items[] = array("id"=>"SN","name"=>"Senegal","category_id"=>1,"capital"=>"Dakar");
        $items[] = array("id"=>"RS","name"=>"Serbia","category_id"=>3,"capital"=>"Belgrade");
        $items[] = array("id"=>"SC","name"=>"Seychelles","category_id"=>1,"capital"=>"Victoria");
        $items[] = array("id"=>"SL","name"=>"Sierra Leone","category_id"=>1,"capital"=>"Freetown");
        $items[] = array("id"=>"SG","name"=>"Singapore","category_id"=>2,"capital"=>"Singapore");
        $items[] = array("id"=>"SX","name"=>"Sint Maarten","category_id"=>4,"capital"=>"Philipsburg");
        $items[] = array("id"=>"SK","name"=>"Slovakia","category_id"=>3,"capital"=>"Bratislava");
        $items[] = array("id"=>"SI","name"=>"Slovenia","category_id"=>3,"capital"=>"Ljubljana");
        $items[] = array("id"=>"SB","name"=>"Solomon Islands","category_id"=>6,"capital"=>"Honiara");
        $items[] = array("id"=>"SO","name"=>"Somalia","category_id"=>1,"capital"=>"Mogadishu");
        $items[] = array("id"=>"ZA","name"=>"South Africa","category_id"=>1,"capital"=>"Pretoria");
        $items[] = array("id"=>"GS","name"=>"South Georgia and the South Sandwich Islands","category_id"=>7,"capital"=>"King Edward Point");
        $items[] = array("id"=>"KR","name"=>"South Korea","category_id"=>2,"capital"=>"Seoul");
        $items[] = array("id"=>"SS","name"=>"South Sudan","category_id"=>1,"capital"=>"Juba");
        $items[] = array("id"=>"ES","name"=>"Spain","category_id"=>3,"capital"=>"Madrid");
        $items[] = array("id"=>"LK","name"=>"Sri Lanka","category_id"=>2,"capital"=>"Sri Jayawardenepura Kotte, Colombo");
        $items[] = array("id"=>"PS","name"=>"State of Palestine","category_id"=>2,"capital"=>"Ramallah");
        $items[] = array("id"=>"SD","name"=>"Sudan","category_id"=>1,"capital"=>"Khartoum");
        $items[] = array("id"=>"SR","name"=>"Suriname","category_id"=>5,"capital"=>"Paramaribo");
        $items[] = array("id"=>"SJ","name"=>"Svalbard and Jan Mayen","category_id"=>3,"capital"=>"Longyearbyen");
        $items[] = array("id"=>"SZ","name"=>"Swaziland","category_id"=>1,"capital"=>"Lobamba, Mbabane");
        $items[] = array("id"=>"SE","name"=>"Sweden","category_id"=>3,"capital"=>"Stockholm");
        $items[] = array("id"=>"CH","name"=>"Switzerland","category_id"=>3,"capital"=>"Bern");
        $items[] = array("id"=>"SY","name"=>"Syrian Arab Republic","category_id"=>2,"capital"=>"Damascus");
        $items[] = array("id"=>"TW","name"=>"Taiwan","category_id"=>2,"capital"=>"Taipei");
        $items[] = array("id"=>"TJ","name"=>"Tajikistan","category_id"=>2,"capital"=>"Dushanbe");
        $items[] = array("id"=>"TZ","name"=>"Tanzania","category_id"=>1,"capital"=>"Dodoma");
        $items[] = array("id"=>"TH","name"=>"Thailand","category_id"=>2,"capital"=>"Bangkok");
        $items[] = array("id"=>"TL","name"=>"Timor-Leste","category_id"=>2,"capital"=>"Dili");
        $items[] = array("id"=>"TG","name"=>"Togo","category_id"=>1,"capital"=>"Lomé");
        $items[] = array("id"=>"TK","name"=>"Tokelau","category_id"=>6,"capital"=>"Nukunonu, Atafu,Tokelau");
        $items[] = array("id"=>"TO","name"=>"Tonga","category_id"=>6,"capital"=>"Nukuʻalofa");
        $items[] = array("id"=>"TT","name"=>"Trinidad and Tobago","category_id"=>5,"capital"=>"Port of Spain");
        $items[] = array("id"=>"TN","name"=>"Tunisia","category_id"=>1,"capital"=>"Tunis");
        $items[] = array("id"=>"TR","name"=>"Turkey","category_id"=>2,"capital"=>"Ankara");
        $items[] = array("id"=>"TM","name"=>"Turkmenistan","category_id"=>2,"capital"=>"Ashgabat");
        $items[] = array("id"=>"TC","name"=>"Turks and Caicos Islands","category_id"=>4,"capital"=>"Cockburn Town");
        $items[] = array("id"=>"TV","name"=>"Tuvalu","category_id"=>6,"capital"=>"Funafuti");
        $items[] = array("id"=>"UG","name"=>"Uganda","category_id"=>1,"capital"=>"Kampala");
        $items[] = array("id"=>"UA","name"=>"Ukraine","category_id"=>3,"capital"=>"Kiev");
        $items[] = array("id"=>"AE","name"=>"United Arab Emirates","category_id"=>2,"capital"=>"Abu Dhabi");
        $items[] = array("id"=>"GB","name"=>"United Kingdom","category_id"=>3,"capital"=>"London");
        $items[] = array("id"=>"UM","name"=>"United States Minor Outlying Islands","category_id"=>4,"capital"=>"Washington, D.C.");
        $items[] = array("id"=>"US","name"=>"United States of America","category_id"=>4,"capital"=>"Washington, D.C.");
        $items[] = array("id"=>"UY","name"=>"Uruguay","category_id"=>5,"capital"=>"Montevideo");
        $items[] = array("id"=>"UZ","name"=>"Uzbekistan","category_id"=>2,"capital"=>"Tashkent");
        $items[] = array("id"=>"VU","name"=>"Vanuatu","category_id"=>6,"capital"=>"Port Vila");
        $items[] = array("id"=>"VE","name"=>"Venezuela (Bolivarian Republic of)","category_id"=>5,"capital"=>"Caracas");
        $items[] = array("id"=>"VN","name"=>"Vietnam","category_id"=>2,"capital"=>"Hanoi");
        $items[] = array("id"=>"VG","name"=>"Virgin Islands (British)","category_id"=>4,"capital"=>"Road Town");
        $items[] = array("id"=>"VI","name"=>"Virgin Islands (U.S.)","category_id"=>4,"capital"=>"Charlotte Amalie");
        $items[] = array("id"=>"WF","name"=>"Wallis and Futuna","category_id"=>6,"capital"=>"Mata-Utu");
        $items[] = array("id"=>"EH","name"=>"Western Sahara","category_id"=>1,"capital"=>"Laayoune");
        $items[] = array("id"=>"YE","name"=>"Yemen","category_id"=>2,"capital"=>"Sana'a");
        $items[] = array("id"=>"ZM","name"=>"Zambia","category_id"=>1,"capital"=>"Lusaka");
        $items[] = array("id"=>"ZW","name"=>"Zimbabwe","category_id"=>1,"capital"=>"Harare");

        foreach ($items as $key => $value) {
            $category = $this->flagCategory($value['category_id']);
            $items[$key]['category_name'] = !empty($category['name']) ? $category['name'] : '';
        }

        return $items;
    }

    public function flagCategory($id='')
    {
        $items[] = array("id"=>0,"name"=>'World');
        $items[] = array("id"=>1,"name"=>'Africa');
        $items[] = array("id"=>2,"name"=>'Asia');
        $items[] = array("id"=>3,"name"=>'Europe');
        $items[] = array("id"=>4,"name"=>'North America');
        $items[] = array("id"=>5,"name"=>'South America');
        $items[] = array("id"=>6,"name"=>'Oceania');
        $items[] = array("id"=>7,"name"=>"Antarctica");

        if( !empty($id) ){
            return !empty($items[$id])? $items[$id]: array();
        }
        else{
            return $items;
        }
        
    }


    public function _defaultPriceValue()
    {
        $prices = array();
        $prices[] = array('id'=>'adult', 'name'=> 'Adult', 'key'=> 'price_1', 'is_pax'=>1, 'is_sharecom'=>true);
        $prices[] = array('id'=>'child', 'name'=> 'Child', 'key'=> 'price_2', 'is_pax'=>1, 'is_sharecom'=>true);
        $prices[] = array('id'=>'childNoBed', 'name'=> 'Child No Bed', 'key'=> 'price_3', 'is_pax'=>1, 'is_sharecom'=>true);
        $prices[] = array('id'=>'infant', 'name'=> 'Infant', 'key'=> 'price_4');
        $prices[] = array('id'=>'joinland', 'name'=> 'Joinland', 'key'=> 'price_5', 'is_sharecom'=>true);

        return $prices;
    }

    public function _roomOfType()
    {
        $rooms = array();
        $rooms[] = array('id'=>'twin', 'name'=>'Twin', 'quota'=>2);
        $rooms[] = array('id'=>'double', 'name'=>'Double', 'quota'=>2);
        $rooms[] = array('id'=>'triple', 'name'=>'Triple', 'quota'=>3);
        $rooms[] = array('id'=>'tripletwin', 'name'=>'Triple(Twin)', 'quota'=>3);
        $rooms[] = array('id'=>'single', 'name'=>'Single', 'quota'=>1);

        return $rooms;
    }
    
    public function _defaultRoomOfTypes()
    {
        $rooms = array();
        $rooms['Twin'] = 2;
        $rooms['Double'] = 2;
        $rooms['Triple'] = 3;
        $rooms['Triple(Twin)'] = 3;
        $rooms['Single'] =1;

        return $rooms;
    }
}
