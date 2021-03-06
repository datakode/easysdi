<?php
/**
 * @version     4.4.5
 * @package     com_easysdi_monitor
 * @copyright   Copyright (C) 2013-2017. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      EasySDI Community <contact@easysdi.org> - http://www.easysdi.org
 */
 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class Easysdi_monitorController extends JControllerLegacy
{
    
/**
	 * Method to display the view
	 *
	 * @access	public
	 */
        public function display($cachable = false, $urlparams = false)
	{
		// Get the document object.
		$document = JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName = $this->input->get('view', 'proxy');
		$this->input->set('view', $vName);

		parent::display($cachable);

		return $this;
	}
	
	function create(){
		$database=& JFactory::getDBO(); 
		$User =& JFactory::getUser();		
		$usertype= strtolower($User->usertype);	  	
		$row =	json_decode(JRequest::getVar("rows"));	
	  	if(strpos($usertype, "admin")=== FALSE){
			echo "{success:false, error:user is not admin}";
			die();
		}
                
                $query = $database->getQuery(true);
                
                $columns = array('exportName', 'exportType', 'xsltUrl','exportDesc');
                $values = array($query->quote($row->exportName), $query->quote($row->exportType), $query->quote($row->xsltUrl), $query->quote($row->exportDesc));
                $query->insert('#__sdi_monitor_exports');
                $query->columns($query->quoteName($columns));
                $query->values(implode(',', $values));
                
		
		try{
			
			$database->setQuery($query);
			$rows = $database->loadObjectList();
			echo "{success:true}";
					
		
		}
		catch(Exception $e){
			echo "{success:false, error:".$e->getTraceAsString()."}";
		}
		die();
		
	
	}
	function read(){
		
		echo "read";
		die();
		$database=& JFactory::getDBO(); 
		$User = JFactory::getUser();		
		$usertype= strtolower($User->usertype);	  		
	  	if(strpos($usertype, "admin")=== FALSE){
			echo "{success:false, error:user is not admin}";
			die();
		}
		
		$limit		=  JRequest::getVar('limit', 15);
		$limitstart	=  JRequest::getVar('start', 0);
		//get the total
                $query = $database->getQuery(true);
                $query->select('count(*)');
                $query->from('#__sdi_monitor_exports');
                
		$database->setQuery($query);
		$total = $database->loadResult();
		$pagination = new JPagination($total,$limitstart,$limit);
		
                $query = $database->getQuery(true);
                $query->select('*');
                $query->from('#__sdi_monitor_exports');
                $query->order('id desc');
              
		
		try{
			
			$database->setQuery( $query, $pagination->limitstart, $pagination->limit);
			$rows = $database->loadObjectList();
			echo "{success:true,results:".$total.",rows:".json_encode($rows)."}";
					
		
		}
		catch(Exception $e){
			echo "{success:false, error:".$e->getTraceAsString()."}";
		}
		die();
	
	}
	function update(){
		//"id":"1","exportName":"name1","exportType":"csw","exportDesc":"desc111","xsltUrl":"url1"
		$database=& JFactory::getDBO(); 
		$User =& JFactory::getUser();
		$usertype= strtolower($User->usertype);	  	
		$row =	json_decode(JRequest::getVar("rows"));
	  	if(strpos($usertype, "admin")=== FALSE){
			echo "{success:false, error:user is not admin}";
			die();
		}
                
                $database->updateObject('#__sdi_monitor_exports', $row, $row->id);
		
		$result =$database->query();
		if ($database->getErrorNum()) {
				
			echo "{success:false, error:".$e->getTraceAsString()."}";
			die();
		
		}
		echo "{success:true}";
		die();
	
		
	
	}
	function delete(){
		
		$database=& JFactory::getDBO(); 
		$User =& JFactory::getUser();		
		$usertype= strtolower($User->usertype);	
		$id =	JRequest::getVar("rows");  		
	  	if(strpos($usertype, "admin")=== FALSE){
			echo "{success:false, error:user is not admin}";
			die();
		}
	
                $query = $database->getQuery(true);
                $query->delete('#__sdi_monitor_exports');
                $query->where('id ='. (int)$id);
                
		$database->setQuery($query);
		$result =$database->query();
		if ($database->getErrorNum()) {
				
			echo "{success:false, error:".$e->getTraceAsString()."}";
			die();
		
		}
		echo "{success:true}";
		die();
		
		
	
	}
	
}