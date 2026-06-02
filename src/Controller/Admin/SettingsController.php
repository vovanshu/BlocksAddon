<?php declare(strict_types=1);
namespace BlocksAddon\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Form\Form;
use Omeka\Stdlib\Message;
use Omeka\Form\ConfirmForm;
// use Common\Form\Element as CommonElement;
use Omeka\Form\Element\PropertySelect;
use BlocksAddon\TraitGeneral;

class SettingsController extends AbstractActionController
{

    use TraitGeneral;

    public function editAction()
    {

        $form = $this->getForm(Form::class);

        $configs = $this->getConfigs();
        $blocks = [];
        if(!empty($configs['block_layouts']['invokables'])){
            foreach($configs['block_layouts']['invokables'] as $key => $obj){
                $blk = new $obj;
                $blocks[$key] = $blk->getLabel();
            }
        }
        if(!empty($configs['block_layouts']['factories'])){
            foreach($configs['block_layouts']['factories'] as $key => $obj){
                $fact = new $obj;
                $blk = $fact->__invoke($this->getServiceLocator(), $key);
                $blocks[$key] = $blk->getLabel();
            }
        }
        asort($blocks);
        if(!empty($blocks)){
            foreach($blocks as $key => $title){
                $label = $this->translate('Disable block');
                if($title !== 'Block disabled'){
                    $label .= ' - '.$this->translate($title);
                }else{
                    $label .= ' - ('.$key.')';
                }
                $form->add([
                    'name' => $key,
                    'type' => 'checkbox',
                    'options' => [
                        'label' => $label,
                        'checked_value' => 'true',
                        'unchecked_value' => 'false',
                    ],
                    'attributes' => [
                        'id' => $key,
                        'value' => $this->getCustomConfval('block_layouts_disabled', $key),
                    ],
                ]);
            }
        }

        $this->saveSettings($blocks);

        $view = new ViewModel;
        $view->setVariable('form', $form);
        return $view;

    }

    // public function getCustomConfval($key){
    //     $val = $this->getConf('block_layouts_disabled', $key);
    //     if(!empty($val) && $val == $key){
    //         return 'true';
    //     }
    //     return 'false';
    // }

    public function getCustomConfVal($key, $child = Null)
    {

        // if($this->isSetNotInCustomConf($key)){
        //     return$this->getConf($key);
        // }else{
            if(!empty($child)){
                $def = $this->getConf('custom_configs', $key.'_'.$child);
            }else{
                $def = $this->getConf('custom_configs', $key);
            }
            $file = OMEKA_PATH . '/config/custom.config.php';
            $config = [];
            if(file_exists($file)){
                if(function_exists('opcache_invalidate')){
                    opcache_invalidate($file, true);    
                }
                $config = (include $file);
            }
            if(!empty($config) && !empty($config['BlocksAddon'][$key])){
                if(!empty($child) && !empty($config['BlocksAddon'][$key][$child]) &&($config['BlocksAddon'][$key][$child] == $child || $config['BlocksAddon'][$key][$child] == 'true')){
                    return 'true'; 
                }elseif($config['BlocksAddon'][$key] == $key || $config['BlocksAddon'][$key] == 'true'){
                    return 'true'; 
                }elseif($def !== False){
                    return $def;
                }                
            }
        // }
        return 'false';
    }

    // public function isSetNotInCustomConf($key)
    // {

    //     if($this->getConf($key) !== False){
    //         $file = OMEKA_PATH . '/config/custom.config.php';
    //         $config = file_exists($file) ? include $file : [];
    //         if(empty($config) || !isset($config['AdminAddon'][$key])){
    //             return True;
    //         }
    //     }
    //     return False;

    // }

    private function saveSettings($blocks)
    {

        $request = $this->getRequest();
        if ($request->isPost()) {
            // $ops_custom_configs = $this->getConf('custom_configs');

            $file = OMEKA_PATH . '/config/custom.config.php';
            $config = file_exists($file) ? include $file : [];

            $post = $request->getPost()->toArray();
            foreach($blocks as $key => $title){
                // $name = 'blocksaddon_'.$key.'_enable';
                if(isset($post[$key])){
                    // $this->setSets($name, $post[$name]);
                    // if(in_array($key, $ops_custom_configs)){
                    $config = $this->prepCustomConfig($config, $key, $post[$key]);
                    // }
                }
            }

            $content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
            if(file_put_contents($file, $content)){
                $this->messenger()->addSuccess('Settings save successfully.'); // @translate
            }else{
                $this->messenger()->addError('Settings save failed.'); // @translate
            }

            return $this->redirect()->refresh();
        }

    }

    private function prepCustomConfig($config, $key, $val)
    {

        if($val == 'true'){
            $config['BlocksAddon']['block_layouts_disabled'][$key] = $key;
        }else{
            if(isset($config['BlocksAddon']['block_layouts_disabled'][$key])){
                unset($config['BlocksAddon']['block_layouts_disabled'][$key]);
            }
        }
        if(isset($config['BlocksAddon']['block_layouts_disabled']) && empty($config['BlocksAddon']['block_layouts_disabled'])){
            unset($config['BlocksAddon']['block_layouts_disabled']);
        }
        if(isset($config['BlocksAddon']) && empty($config['BlocksAddon'])){
            unset($config['BlocksAddon']);
        }

        return $config;

    }

    // public function updoctrineAction()
    // {

    //     if ($this->isAppDevMode() && $this->userIsAllowed('BlocksAddon\Controller\Admin\SettingsController', 'updoctrine')){
    //         $params = [
    //             'process' => 'UpdateDoctrine',
    //         ];
    //         $this->jobDispatcher()->dispatch(\BlocksAddon\Job\UpdateDoctrine::class, $params);
    //         $message = new Message(
    //             'Update Doctrine Module add to Jobs.' // @translate
    //         );
    //         $this->messenger()->addSuccess($message);
    //     }else{
    //         $message = new Message(
    //             'Update Doctrine Module not allowed.' // @translate
    //         );
    //         $this->messenger()->addError($message);
    //     }
    //     return $this->redirect()->toRoute('admin/blocks-addon-settings', ['action' => 'edit']);

    // }

    public function uplocaletplAction()
    {

        if ($this->isAppDevMode() && $this->userIsAllowed('BlocksAddon\Controller\Admin\SettingsController', 'uplocaletpl')){
            $params = [
                'process' => 'UpdateLocaleTemplate',
            ];
            $this->jobDispatcher()->dispatch(\BlocksAddon\Job\UpdateLocaleTemplate::class, $params);
            $message = new Message(
                'Update Locale template add to Jobs.' // @translate
            );
            $this->messenger()->addSuccess($message);
        }else{
            $message = new Message(
                'Update Locale template not allowed.' // @translate
            );
            $this->messenger()->addError($message);
        }
        return $this->redirect()->toRoute('admin/blocks-addon-settings', ['action' => 'edit']);

    }


    public function backupsAction()
    {

        $path = $this->getConf('backups');
        $list = glob($path.'*.sql');
        $view = new ViewModel;
        $view->setVariable('list', $list);
        return $view;

    }

    public function backupingAction()
    {

        $settings = $this->getConf('settings');
        
        // if($this->getSets('adminaddon_backup_resource_template')){
        //     $tables = ['vocabulary', 'resource_class', 'property', 'resource_template', 'resource_template_data', 'resource_template_property', 'resource_template_property_data'];
        // }else{
        //     $tables = ['vocabulary', 'resource_class', 'property'];
        // }
        $tables = [];
        $path = $this->getConf('backups');
        $r = $this->backuping_data($settings, $tables, $path);
        $view = new ViewModel;
        $view->setVariable('result', $r);
        return $view;

    }
    
    public function restoreAction()
    {

        $name = $this->params('name');
        $path = $this->getConf('backups');
        if(file_exists($path.$name)){
            $sql = "SET FOREIGN_KEY_CHECKS=0;";
            $sql .= file_get_contents($path.$name);
            $sql .= "SET FOREIGN_KEY_CHECKS=1;";
            try{
                $result = $this->getConnection()->executeStatement($sql);
                $this->messenger()->addSuccess('Restore successfully.'); // @translate
            }catch(\Exception $e){
                $this->getLogger()->err((string) $e);
                $this->messenger()->addError('Restore failed!'); // @translate
            }
        }else{
            $this->messenger()->addError('Restore failed - file no found!'); // @translate
        }
        return $this->redirect()->toRoute('admin/blocks-addon-settings', ['action' => 'backups']);
    }

    public function restoreConfirmAction()
    {

        $name = $this->params('name');
        $path = $this->getConf('backups');
        $info = $this->infoAboutBackup($path.$name);
        $form = $this->getForm(ConfirmForm::class);
        $form = $this->getForm(ConfirmForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('admin/blocks-addon-settings', ['action' => 'restore', 'name' => $name]));
        $view = new ViewModel();
        $view->setVariable('form', $form);
        $view->setVariable('file', $name);
        $view->setVariable('info', $info);
        $view->setTemplate('admin-addon/admin/settings/restore-confirm');
        return $view->setTerminal(true);

    }

    public function deleteAction()
    {

        if ($this->getRequest()->isPost()) {
            $form = $this->getForm(ConfirmForm::class);
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $name = $this->params('name');
                $path = $this->getConf('backups');
                if (unlink($path.$name)) {
                    $this->messenger()->addSuccess('File backup successfully deleted.'); // @translate
                }
            } else {
                $this->messenger()->addFormErrors($form);
            }
        }
        return $this->redirect()->toRoute('admin/blocks-addon-settings', ['action' => 'backups']);

    }

    public function deleteConfirmAction()
    {

        $name = $this->params('name');
        $path = $this->getConf('backups');
        $info = $this->infoAboutBackup($path.$name);
        $form = $this->getForm(ConfirmForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('admin/blocks-addon-settings', ['action' => 'delete', 'name' => $name]));
        $view = new ViewModel();
        $view->setVariable('form', $form);
        $view->setVariable('file', $name);
        $view->setVariable('info', $info);
        $view->setTemplate('admin-addon/admin/settings/delete-confirm');
        return $view->setTerminal(true);

    }

    public function detailsAction()
    {

        $name = $this->params('name');
        $path = $this->getConf('backups');
        $info = $this->infoAboutBackup($path.$name);
        $view = new ViewModel();
        $view->setVariable('file', $name);
        $view->setVariable('info', $info);
        return $view->setTerminal(true);

    }

    private function infoAboutBackup($file)
    {

        $content = file_get_contents($file);
        if(stripos($content, 'Begin backup DB') !== False){
            $rc = explode("--\n--  Begin backup DB\n\n\n", $content);
            $r = strtr($rc[0], ["\n" => '<br>', '--' => '']);
        }else{
            $r = 'Information about backup no foud!';
        }
        return $r;

    }

    private function backuping_data($settings, $tables, $path) 
    {

        $time_zone = $this->getSets('time_zone');
        date_default_timezone_set($time_zone);
        $r['timestamp'] = $timestamp = date('Y-m-d H:i:s');
        $dest = $path.date('Y-m-d-H-i-s').'.sql';

        $reader = new \Laminas\Config\Reader\Ini;
        $db = $reader->fromFile(OMEKA_PATH . '/config/database.ini');

        $link = mysqli_connect($db['host'],$db['user'],$db['password'], $db['dbname']);
        mysqli_query($link, "SET NAMES 'utf8'");

        $result = '';
        $result .= "--\n-- Backup Settings\n--\n\n";

        $oi = 1;
        foreach($settings as $name => $defval){
            $value = $this->getSets($name);
            if(!empty($value)){
                if(is_array($value)){
                    $value = json_encode($value);
                }elseif(is_string($value)){
                    $value = strtr($value, ["\r"=> '\r', "\n"=> '\n']);
                    $value = '"'.$value.'"';
                }
                $value = addslashes($value);
                $result .= "DELETE FROM `setting` WHERE `id` = '$name';\n";
                $result .= "INSERT INTO setting VALUES('$name', '$value');\n";
                $totalCount['Settings'] = $oi;
                $oi++;
            }
        }
        $result.="\n\n\n";
        
        foreach($tables as $table)
        {

            $rc = mysqli_query($link, "SELECT * FROM `$table`;");
            $num_fields = mysqli_num_fields($rc);
            $num_rows = mysqli_num_rows($rc);

            $result.= "--\n-- Backup table $table\n--\n\n";
            $result.= 'DROP TABLE IF EXISTS '.$table.';';

            $createTable = mysqli_fetch_row(mysqli_query($link, "SHOW CREATE TABLE `$table`;"));
            $result.= "\n\n".$createTable[1].";\n\n";
            $counter = 1;

            //Over tables
            for ($i = 0; $i < $num_fields; $i++){
            //Over rows
                while($row = mysqli_fetch_row($rc)){   
                    if($counter == 1){
                        $result.= 'INSERT INTO '.$table.' VALUES(';
                    } else{
                        $result.= '(';
                    }

                    //Over fields
                    for($j=0; $j<$num_fields; $j++) 
                    {
                        if(is_string($row[$j])){
                            $row[$j] = addslashes($row[$j]);
                            $row[$j] = str_replace("\n","\\n",$row[$j]);
                        }
                        if(isset($row[$j])) {
                            $result.= '"'.$row[$j].'"' ;
                        }else{
                            $result.= 'Null';
                        }
                        if($j<($num_fields-1)){
                            $result.= ',';
                        }
                    }

                    if($num_rows == $counter){
                        $result.= ");\n";
                    } else{
                        $result.= "),\n";
                    }
                    $counter++;
                }
                $totalCount[$table] = $counter-1;
            }
            $result.="\n\n\n";
        }

        $head = "--    Info about Backup\n--\n--   Timestampe = $timestamp\n\n--   Total count\n";
        foreach($totalCount as $k => $v){
            $r[$k] = $v;
            $head .= "--   $k = $v\n";
        }
        $head .= "--\n--  Begin backup DB\n\n\n";

        $result = $head.$result;
        if(!file_exists($path)){
            mkdir($path, 0755, True);
        }
        if(!file_exists(dirname($path).'/.htaccess')){
            file_put_contents(dirname($path).'/.htaccess', "
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
    Order Allow,Deny
    Deny from all
</IfModule>
");
        }
        file_put_contents($dest, $result);
        return $r;

    }

}
