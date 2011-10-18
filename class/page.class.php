<?

require_once("functions.php");


// Create a wrapper class extended from Smarty
class Page extends Smarty
{
    private $pageTitle;
    private $pageKeywords;
    private $primaryObject;         	// Optional
    private $javascriptFileArray;   	// Extra js files to be included
    private $internalJavascriptArray; 	// internal JS Codeblocks for use only on this page
    private $styleSheetArray;       	// Extra stylesheets to be used
    private $onloadArray;				// JS Commands to be run at pageload
    private $mainTemplate;
    private $contentTemplate;
	private $jqueryExists;

    // $cache and $cache_lifetime are the two main variables
    // that control caching within Smarty
    function Page($cache = false, $cache_lifetime = 300)
    {
        global $me;
        // Run Smarty's constructor
        
        //$this->listener = new Listener(); // Instantiate Listener Class

        parent::__construct();
        //$this->Smarty();

        // Change the default template directories
        $this->template_dir	= TEMPLATE_DIR;
        $this->compile_dir	= COMPILE_DIR;
        $this->config_dir	= CONFIG_DIR;
        $this->cache_dir	= CACHE_DIR;
        
       // die("Template dir= " . $this->template_dir);

        // Change default caching behavior
        $this->caching = $cache;
        $this->cache_lifetime = $cache_lifetime;

        $this->javascriptFile = array(); // Initialize to empty

        // Set some defaults until told otherwise..
        $this->setTitle(GLOBAL_TITLE);
        $this->setKeywords(GLOBAL_KEYWORDS);
        $this->setDescription(GLOBAL_DESCR);
        $this->setTemplate(DEFAULT_TEMPLATE);
        
        $this->addJS('script.js');
        
        if(Security::isMember())
            $this->assign('me',$me);
            
    }
    function setTitle($title)
    {
        $this->assign('pageTitle',$title);
    }
    function setDescription($description)
    {
        $this->assign('pageDescription',$description);
    }
    function setKeywords($keywords)
    {
        $this->assign('pageKeywords',$keywords);
    }
    function setSuccess($message)
    {
        if(strlen($message) > 2)
        {
            $this->assign('success',true);
            $this->assign('notice',$message);
            
            return true;
        }
        
        return false;
    }
    function setError($message,$kill=false)
    {
        if(is_array($message))
        {
            foreach($message as $msg)
            {
                $this->setError($message); // Recursive goodness
            }
        }
        else if(strlen($message) > 2)
        {
            $this->assign('error',true);
            $this->assign('notice',$message);
            
            if($kill) { $this->create(); exit(); }
            
            return true;
        }
        
        return false;
    }
    function setWarning($message)
    {
       if(strlen($message) > 2)
        {
            $this->assign('warning',true);
            $this->assign('notice',$message);
            
            return true;
        }
        
        return false;
    }    
	function setPrimary($object)
    {
    	if($this->checkObject($object))
    	{
			$this->primaryObject = $object;
			return true;
		}
		else
		{
			submitReport('NonObj Primary Page.Class~'.__LINE__);
			return false;
		}
    }
    /*
        Description: addJS( $sourceFile );

                Can be called any number of times to assign javascript file(s) to the page.
                Use good practice and call from $this in every case possible

        Example:

                $page->addJS('myJavascriptFile.js');

                Refers to /lib/javascript/myJavascriptFile.js

        Similarly:
                $page->addJS('someFolder/myFile.js');

        Offsite Files:
                $page->addJS('somesite.com/script.js',false);
    */
    function addJS($sourceFile,$appendFilePath = true)
    {
        if(!is_array($sourceFile) && !empty($sourceFile))
        {
            if($appendFilePath)
                $this->javascriptFileArray[] = "/script/" . $sourceFile;

            else
                $this->javascriptFileArray[] = $sourceFile;
        }
        else if(is_array($sourceFile))
        {
            foreach($sourceFile as $file)
                $this->addJS($file,$appendFilePath); // Recursionnnnn
        }
        else return false;
    }
    /* Adds a free block of javascript code to the page */
    function addInternalJS($script)
    {
    	 if(!is_array($script) && !empty($script))
        {
            $this->internalJavascriptArray[] = $script;
        }
        else if(is_array($script))
        {
            foreach($script as $codeblock)
                $this->addInternalJS($codeBlock); // Recursionnnnn
        }
        else
        	return false;
    }
    function getInternalJS()
    {
    	return $this->internalJavascriptArray;
    }
    function addStyleSheet($sourceFile,$appendFilePath = true)
    {
        if($appendFilePath)
            $this->styleSheetArray[] = "/style/" . $sourceFile;

        else
            $this->styleSheetArray[] = $sourceFile;

    }
    /*
    function addListener($caller,$args=NULL)
    {
        $this->listener->$caller($args);

        //call_user_func(__NAMESPACE__ .'\Listener::'.$caller,$args);
    }
    */
    private function checkObject($object)
    {
        if(!is_object($object))
        {
            //submitReport("Non-Object on Page.class ".__LINE__);
            return false;
        }
        else
    		return true;
    }
    function setTemplate($mainTemplate)
    {
        // Should probably make sure file exists...
        $this->mainTemplate = $mainTemplate;
    }
    function setContent($contentTemplate)
    {
        // Should probably make sure file exists...
        $this->contentTemplate = $contentTemplate;
    }
    function getTemplate()
    {
        return $this->mainTemplate;
    }
    function getContentTemplate()
    {
        return $this->contentTemplate;
    }
    private function getJSFiles()
    {
        return $this->javascriptFileArray;
    }
    private function getStyleSheets()
    {
        return $this->styleSheetArray;
    }
    function addOnload($jsCommand)
    {
        if(!is_array($jsCommand))
            $this->onloadArray[] = $jsCommand;

        else
        {
            foreach($jsCommand as $cmd)
                $this->onloadArray[] = $cmd;
        }
    }
    private function getOnloadCommands()
    {
        return $this->onloadArray;
    }
    function create()
    {
        $this->assign('jsFiles',$this->getJSFiles());
        $this->assign('internalJS',$this->getInternalJS());
        $this->assign('styleSheets',$this->getStyleSheets());
        $this->assign('onloadCommands',$this->getOnloadCommands());

        $this->assign('content',$this->getContentTemplate());

        $this->display( $this->getTemplate() );
    }
    function notfound()
    {
        header("Location: /page/pageNotFound.php");
        die("Page Currently Unavailable.  Security:".__LINE__); // Just in case headers were already sent to the page
    }
}

?>
