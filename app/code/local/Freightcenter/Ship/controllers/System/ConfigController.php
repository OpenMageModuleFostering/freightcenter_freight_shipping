<?php
require_once 'Mage/Adminhtml/controllers/System/ConfigController.php';

class Freightcenter_Ship_System_ConfigController extends Mage_Adminhtml_System_ConfigController
{
    public function saveAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        /* @var $session Mage_Adminhtml_Model_Session */

        $groups = $this->getRequest()->getPost('groups');
        
        if(isset($groups['freightcenter'])) {
            /* function to convert xml to array */
            function xmlToArray($input, $callback = null, $recurse = false) {
                    $data = ((!$recurse) && is_string($input))? simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA): $input;
                    if ($data instanceof SimpleXMLElement) $data = (array) $data;
                    if (is_array($data)) foreach ($data as &$item) $item = xmlToArray($item, $callback, true);
                    return (!is_array($data) && is_callable($callback))? call_user_func($callback, $data): $data;
            }
			$current_mode = $groups['freightcenter']['fields']['sandbox']['value'];
			   $username = $groups['freightcenter']['fields']['freightuser']['value'];
            $password = $groups['freightcenter']['fields']['freightpwd']['value'];
			/* if($current_mode == '1' ) {
			 $api_url_hit = 'http://sandbox.freightcenter.com/v04/carriers.asmx?op=GetActiveCarriers';
			   $xml='<?xml version="1.0" encoding="utf-8"?>
                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                  <soap12:Body>
                    <GetActiveCarriers xmlns="http://freightcenter.com/API/V04/">
                      <request>
                            <Username>'.$username.'</Username>
                            <Password>'.$password.'</Password>
                            <LicenseKey>3BDA740C-FF39-4BDC-AB49-B0A3C08E23B4</LicenseKey>
                        </request>
                    </GetActiveCarriers>
                  </soap12:Body>
                </soap12:Envelope>';
			
			}else{ */
			$api_url_hit = 'http://api.freightcenter.com/v04/carriers.asmx?op=GetActiveCarriers';
			  $xml='<?xml version="1.0" encoding="utf-8"?>
                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                  <soap12:Body>
                    <GetActiveCarriers xmlns="http://freightcenter.com/API/V04/">
                      <request>
                            <Username>'.$username.'</Username>
                            <Password>'.$password.'</Password>
                            <LicenseKey>3BDA740C-FF39-4BDC-AB49-B0A3C08E23B4</LicenseKey>
                        </request>
                    </GetActiveCarriers>
                  </soap12:Body>
                </soap12:Envelope>';
		//	}
			
            //echo "<pre>";print_r($groups['freightcenter']);exit;
         
            //$license_key = $groups['freightcenter']['fields']['freightlicensekey']['value'];
            
            /* xml request */
          
          
           /*  if($current_mode == '1'){
		
			$headers = array(
                "POST /v04/carriers.asmx HTTP/1.1",
				"Host: sandbox.freightcenter.com",
				"Content-Type: application/soap+xml; charset=utf-8",
                "Content-length: ".strlen($xml)
            );
			} else {	 */	
			$headers = array(
                "POST  /V04/carriers.asmx HTTP/1.1",
                "Host: api.freightcenter.com",
                "Content-type: application/soap+xml; charset=utf-8",
                "Content-length: ".strlen($xml)
            );
           // }
            /* send curl request to get active carriers */
            $soap_do = curl_init();
            curl_setopt($soap_do, CURLOPT_URL,            $api_url_hit );
            curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($soap_do, CURLOPT_POST,           true );
            curl_setopt($soap_do, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($soap_do, CURLOPT_POSTFIELDS,    $xml);

            $result = curl_exec($soap_do);
            curl_close($soap_do);
            
            $get_result = preg_replace('/xmlns:.*\"/','',$result);
            $get_response = preg_replace('<soap:>','',$get_result);
            $get_response1 = preg_replace('<</GetActiveCarriersResponse>>','',$get_response);
            $get_response2 = preg_replace('<</Body>>','',$get_response1);
            $get_response3 = preg_replace('<</Text></Reason><Detail /></Fault>>','',$get_response2);
            
            $output = xmlToArray($get_response3);
            //echo "<pre>";print_r($output);exit;

            if(isset($output[0])) {
                $session->addError("Please check credentials!");
            } else {
                $response_type = $output['GetActiveCarriersResult']['TransactionResponse']['Type'];
                if($response_type == 'SUCCESS') {
                    //echo "<pre>";print_r($output);
                    $carriers = $output['GetActiveCarriersResult']['Carriers']['Carrier'];
                    $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                   // echo '<pre>'; print_r($carriers); die('this');
                    $new_carriers = array();
                    foreach($carriers as $carrier) {
                        $carrierid = $carrier['CarrierId'];
                        $carrierscac = $carrier['CarrierSCAC'];
                        $carriercode = $carrier['CarrierCode'];
                        if($carriercode == NULL) {
                            $carriercode = '';
                        }
                        $carriername = $carrier['CarrierName'];
                        
                        $new_carriers[] = $carrier['CarrierId'];
                        
                        $carrier_query = $connection->query("select * from brst_freight_carriers where username='$username' AND carrierid = $carrierid");
                        $getcarrier = $carrier_query->fetchAll();
                        //echo "<pre>";print_r($getcarrier);exit;
                        if($getcarrier == NULL) {
                            $mysql = "INSERT INTO brst_freight_carriers (username,carrierid,carrier_scac,carrier_code,carrier_name) VALUES ('$username',$carrierid,'$carrierscac','$carriercode','$carriername')";
                            $connection->query($mysql);
                        }
                    }
                    
                    $getcar_query = $connection->query("select * from brst_freight_carriers where username = '$username'");
                    $getcarriers = $getcar_query->fetchAll();
                    if($getcarriers != NULL) {
                        $all_carriers = array();
                        foreach($getcarriers as $car) {
                            $all_carriers[] = $car['carrierid'];
                        }
                        
                        $newresult = array_diff($all_carriers,$new_carriers);
						//echo '<pre>'; print_r($newresult);  die('here');
                        if($newresult != NULL) {
                            foreach ($newresult as $nresult) {
							//echo $nresult; 
                            $mysql1 = "Delete from brst_freight_carriers where carrierid = $nresult AND username = '$username'";
                                $connection->query($mysql1);
								//die('here');
                            }
                        }
                    }
                    
                    if (isset($_FILES['groups']['name']) && is_array($_FILES['groups']['name'])) {
                        /**
                         * Carefully merge $_FILES and $_POST information
                         * None of '+=' or 'array_merge_recursive' can do this correct
                         */
                        foreach($_FILES['groups']['name'] as $groupName => $group) {
                            if (is_array($group)) {
                                foreach ($group['fields'] as $fieldName => $field) {
                                    if (!empty($field['value'])) {
                                        $groups[$groupName]['fields'][$fieldName] = array('value' => $field['value']);
                                    }
                                }
                            }
                        }
                    }

                    try {
                        if (!$this->_isSectionAllowed($this->getRequest()->getParam('section'))) {
                            throw new Exception(Mage::helper('adminhtml')->__('This section is not allowed.'));
                        }

                        // custom save logic
                        $this->_saveSection();
                        $section = $this->getRequest()->getParam('section');
                        $website = $this->getRequest()->getParam('website');
                        $store   = $this->getRequest()->getParam('store');
                        Mage::getSingleton('adminhtml/config_data')
                            ->setSection($section)
                            ->setWebsite($website)
                            ->setStore($store)
                            ->setGroups($groups)
                            ->save();

                        // reinit configuration
                        Mage::getConfig()->reinit();
                        Mage::dispatchEvent('admin_system_config_section_save_after', array(
                            'website' => $website,
                            'store'   => $store,
                            'section' => $section
                        ));
                        Mage::app()->reinitStores();

                        // website and store codes can be used in event implementation, so set them as well
                        Mage::dispatchEvent("admin_system_config_changed_section_{$section}",
                            array('website' => $website, 'store' => $store)
                        );
                        $session->addSuccess(Mage::helper('adminhtml')->__('The configuration has been saved.'));
                    }
                    catch (Mage_Core_Exception $e) {
                        foreach(explode("\n", $e->getMessage()) as $message) {
                            $session->addError($message);
                        }
                    }
                    catch (Exception $e) {
                        $session->addException($e,
                            Mage::helper('adminhtml')->__('An error occurred while saving this configuration:') . ' '
                            . $e->getMessage());
                    }

                    $this->_saveState($this->getRequest()->getPost('config_state'));
                    
                } else if($response_type == 'ERROR') {
                    $errormsg = $output['GetActiveCarriersResult']['TransactionResponse']['Message'];
                    $session->addError($errormsg);
                }
            }
            
        } else {
        
            if (isset($_FILES['groups']['name']) && is_array($_FILES['groups']['name'])) {
                /**
                 * Carefully merge $_FILES and $_POST information
                 * None of '+=' or 'array_merge_recursive' can do this correct
                 */
                foreach($_FILES['groups']['name'] as $groupName => $group) {
                    if (is_array($group)) {
                        foreach ($group['fields'] as $fieldName => $field) {
                            if (!empty($field['value'])) {
                                $groups[$groupName]['fields'][$fieldName] = array('value' => $field['value']);
                            }
                        }
                    }
                }
            }

            try {
                if (!$this->_isSectionAllowed($this->getRequest()->getParam('section'))) {
                    throw new Exception(Mage::helper('adminhtml')->__('This section is not allowed.'));
                }

                // custom save logic
                $this->_saveSection();
                $section = $this->getRequest()->getParam('section');
                $website = $this->getRequest()->getParam('website');
                $store   = $this->getRequest()->getParam('store');
                Mage::getSingleton('adminhtml/config_data')
                    ->setSection($section)
                    ->setWebsite($website)
                    ->setStore($store)
                    ->setGroups($groups)
                    ->save();

                // reinit configuration
                Mage::getConfig()->reinit();
                Mage::dispatchEvent('admin_system_config_section_save_after', array(
                    'website' => $website,
                    'store'   => $store,
                    'section' => $section
                ));
                Mage::app()->reinitStores();

                // website and store codes can be used in event implementation, so set them as well
                Mage::dispatchEvent("admin_system_config_changed_section_{$section}",
                    array('website' => $website, 'store' => $store)
                );
                $session->addSuccess(Mage::helper('adminhtml')->__('The configuration has been saved.'));
            }
            catch (Mage_Core_Exception $e) {
                foreach(explode("\n", $e->getMessage()) as $message) {
                    $session->addError($message);
                }
            }
            catch (Exception $e) {
                $session->addException($e,
                    Mage::helper('adminhtml')->__('An error occurred while saving this configuration:') . ' '
                    . $e->getMessage());
            }

            $this->_saveState($this->getRequest()->getPost('config_state'));
        }
        
        $this->_redirect('*/*/edit', array('_current' => array('section', 'website', 'store')));
    }
}

?>