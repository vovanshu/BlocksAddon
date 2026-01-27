<?php declare(strict_types=1);

namespace BlocksAddon;

// use Interop\Container\ContainerInterface;
// use Laminas\ServiceManager\Factory\FactoryInterface;
// use ItemsReview\Controller\Admin\SettingsController;
use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Omeka\Api\Manager as ApiManager;
use Omeka\Api\Representation\AbstractEntityRepresentation;
use Omeka\Entity\AbstractEntity;
use Omeka\Entity\Value;
use Omeka\Permissions\Acl;
use Omeka\Api\Request;
use Omeka\Api\Response;
use Interop\Container\ContainerInterface;

trait Common
{

    protected $serviceLocator;

    protected $requestedName;

    // protected $options;

    protected $acl;

    protected $connection;

    protected $settings;

    protected $config;

    protected $apiManager;

    protected $ApiAdapter = [];

    protected $entityManager;

    protected $logger;

    protected $translator;

    protected $formElementManager;

    /**
     * Set the service locator.
     *
     * @param $serviceLocator
     */
    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get the service locator.
     *
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
    public function getAdapter($resourceName)
    {

        if($this->getServiceLocator()){
            if(empty($this->ApiAdapter[$resourceName])){
                $this->ApiAdapter[$resourceName] = $this->getServiceLocator()->get('Omeka\ApiAdapterManager')->get($resourceName);
            }
            return $this->ApiAdapter[$resourceName];
        }
        return;

    }

    public function getConnection()
    {

        if($this->getServiceLocator()){
            if(!$this->connection){
                $this->connection = $this->getServiceLocator()->get('Omeka\Connection');
            }
            return $this->connection;
        }
        return;

    }

    public function getLogger()
    {

        if($this->getServiceLocator()){
            if(!$this->logger){
                $this->logger = $this->getServiceLocator()->get('Omeka\Logger');
            }
            return $this->logger;
        }
        return;

    }

    public function getApiManager()
    {

        if($this->getServiceLocator()){
            if(!$this->apiManager){
                $this->apiManager = $this->getServiceLocator()->get('Omeka\ApiManager');
            }
            return $this->apiManager;
        }
        return;

    }

    public function getEntityManager()
    {

        if($this->getServiceLocator()){
            if(!$this->entityManager){
                $this->entityManager = $this->getServiceLocator()->get('Omeka\EntityManager');
            }
            return $this->entityManager;
        }
        return;

    }

    public function getAcl()
    {

        if($this->getServiceLocator()){
            if(!$this->acl){
                $this->acl = $this->getServiceLocator()->get('Omeka\Acl');
            }
            return $this->acl;
        }
        return;

    }

    public function getSettings()
    {

        if($this->getServiceLocator()){
            if(!$this->settings){
                $this->settings = $this->getServiceLocator()->get('Omeka\Settings');
            }
            return $this->settings;
        }
        return;

    }

    public function getUserSettings()
    {

        if($this->getServiceLocator()){
            if(!$this->userSettings){
                $this->userSettings = $this->getServiceLocator()->get('Omeka\Settings\User');
            }
            return $this->userSettings;
        }
        return;

    }

    public function getConfigs()
    {

        if($this->getServiceLocator()){
            if(!$this->config){
                $this->config = $this->getServiceLocator()->get('Config');
            }
            return $this->config;
        }
        return;
        
    }

    public function getTranslator()
    {

        if($this->getServiceLocator()){
            if(!$this->translator){
                $this->translator = $this->getServiceLocator()->get('MvcTranslator');
            }
            return $this->translator;
        }
        return;
        
    }

    public function getFormElementManager()
    {

        if($this->getServiceLocator()){
            if(!$this->formElementManager){
                $this->formElementManager = $this->getServiceLocator()->get('FormElementManager');
            }
            return $this->formElementManager;
        }
        return;
        
    }

    public function getConf($name = Null, $param = Null, $all = False)
    {

        $config = $this->getConfigs()['BlocksAddon']['config'];
        if(!empty($name) && !empty($config[$name])){
            if(!empty($param)){
                if(!empty($config[$name][$param])){
                    return $config[$name][$param];
                }else{
                    return False;
                }
            }else{
                return $config[$name];
            }
        }else{
            if($all){
                return $config;
            }else{
                return False;
            }
        }

    }

    public function getOps($name)
    {
        return $this->getConf('options', $name);
    }

    public function getSets($name, $callback = [])
    {
        
        if(!empty($opt = $this->getOps($name))){
            $name = $opt;
        }
        $r = $this->getSettings()->get($name);
        if(!empty($callback)){
            $r = call_user_func_array($callback, [$r]);
        }
        return $r;
        
    }

    public function setSets($name, $value)
    {
        
        if(!empty($opt = $this->getOps($name))){
            $name = $opt;
        }
        $this->getSettings()->set($name, $value);
        
    }

    public function getCurentUserID()
    {

        $user = $this->getAcl()->getAuthenticationService()->getIdentity();
        if($user){
            return $user->getId();
        }
        return Null;

    }
    
    private function getRoleCurentUser()
    {

        $r = 'public';
        $rc = $this->getAcl()->getAuthenticationService()->getIdentity();
        if($rc){
            $r = $rc->getRoleId();
        }
        return $r;

    }

    private function getRoleUser($userID)
    {

        $r = $this->getUserEntry($userID);
        return $r->role();

    }

    private function getUser($userID)
    {

        $rc = $this->getConnection()->executeQuery("SELECT id, name, email, role, created FROM `user` WHERE `id` = '{$userID}' LIMIT 1;");
        if(!empty($rc)){
            return $rc->fetchAssociative();
        }
        return False;

    }

    public function getUserEntry($userID)
    {
        return $this->getAdapter('users')->findEntity($userID);
    }

    public function setPublic($item, $public = True)
    {

        if(!empty($item)){
            if(is_numeric($item)){
                $id = $item;
            }elseif(is_object($item)){
                $id = $item->id();
            }else{
                return False;
            }
            $q = 'UPDATE `resource` SET ';
            if($public){
                $q .= "`is_public` = 1";
            }else{
                $q .= "`is_public` = 0";
            }
            $q .= ' WHERE `id` = '.$id;
            $this->getConnection()->executeQuery($q);
        }

    }

    public function getTplStrByConf($name, $param = Null)
    {

        $rc = $this->getConf($name, $param);
        if(!empty($rc)){
            return $this->translate($rc);
        }
        return False;

    }

    public function translate($message, $textDomain = 'default', $locale = null)
    {
        return $this->getTranslator()->translate($message, $textDomain, $locale);
    }
    
    public function getStrConf($name, $param = Null)
    {

        if(is_array($name)){
            $rc = $this->getConf($name[0], $name[0]);
        }else{
            $rc = $this->getConf($name, Null);
        }
        if(!empty($rc)){
            $str = $this->translate($rc);
            if(!empty($param)){
                return vsprintf($str, $param);
            }else{
                return $str;
            }
            
        }
        return False;

    }

    private function getResourceTemplate($id)
    {

        return $this->getServiceLocator()
            ->get('Omeka\ApiAdapterManager')
            ->get('resource_templates')
            ->findEntity($id);

    }
    
    private function prepProperty($data, $term, $value)
    {

        if(empty($data[$term][0]['@value'])){
            $data[$term][0] = $this->prepPropertyTemplate($data['o:resource_template']['o:id'], $term, $value);
        }else{
            $data[$term][] = $this->prepPropertyTemplate($data['o:resource_template']['o:id'], $term, $value);
        }
        return $data;

    }

    private $cacheTplCont;

    private function prepPropertyTemplate($templateID, $propertieTerm, $value)
    {

        if(!empty($this->cacheTplCont[$templateID])){
            $tplCont = $this->cacheTplCont[$templateID];
        }else{
            $tpl = $this->getResourceTemplate($templateID);
            $this->cacheTplCont[$templateID] = $tplCont = $tpl->getResourceTemplateProperties()->toArray();
        }
        $curTpl = [];
        foreach($tplCont as $k => $v){
            $term = $v->getProperty()->getVocabulary()->getPrefix().':'.$v->getProperty()->getLocalName();
            if($propertieTerm == $term){
                $curTpl = $v;
            }
        }

        $result = [
            'property_id' => $curTpl->getProperty()->getId(),
            'type' => $curTpl->getDataType() ? join(',', $curTpl->getDataType()) : 'literal',
            'is_public' => $curTpl->getIsPrivate() ? '0' : '1',
            '@annotation' => '',
            '@language' => $curTpl->getDefaultLang(),
            '@value' => $value,
            // 'property_term' => $curprop
        ];

        return $result;

    }

    private function setItemValue($itemID, $propertieTerm, $value)
    {


        $item = $this->getAdapter('items')->findEntity($itemID);

        $tplCont = $item->getResourceTemplate()->getResourceTemplateProperties()->toArray();
        $curTpl = [];
        foreach($tplCont as $k => $v){
            $term = $v->getProperty()->getVocabulary()->getPrefix().':'.$v->getProperty()->getLocalName();
            if($propertieTerm == $term){
                $curTpl = $v;
            }
        }

        // echo get_class($item).'<br>';
        // echo get_class($curTpl).'<br>';
        // print_r(get_class_methods($item));
        // print_r(get_class_methods($curTpl));
        // print_r(get_class_methods($curTpl->getProperty()));
        
        // $data = $item->getContent();
        // $tpl = $this->getResourceTemplate($data['o:resource_template']['o:id']);
        $entity = new Value();
        $entity->setResource($item);
        $entity->setProperty($curTpl->getProperty());
        $entity->setType($curTpl->getDataType() ? join(',', $curTpl->getDataType()) : 'literal');
        $entity->setIsPublic($curTpl->getIsPrivate() ? False : True);
        $entity->setLang($curTpl->getDefaultLang());
        $entity->setValue($value);
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        // $this->getEntityManager()->refresh($entity);
        return new Response($entity);

    }

    private function getTemplateForPropContract($data)
    {

        $prop = $this->getSets('propertyofcommon');
        if(!empty($data['o:resource_template']['o:id'])){
            $tpl = $this->getResourceTemplate($data['o:resource_template']['o:id']);
            $rc = $tpl->getResourceTemplateProperties()->toArray();

            foreach($rc as $v){
                $curprop = $v->getProperty()->getVocabulary()->getPrefix().':'.$v->getProperty()->getLocalName();
                if($curprop == $prop){
                    return $v;
                }
                // $curval = $this->getValContractByProp($curprop, $cid, $data['contract'][$cid]);
                // if(isset($data[$prop][$ind]['@annotation'][$curprop][0])){
                    // $data[$prop][$ind]['@annotation'][$curprop][0]['@value'] = $curval;
                // }
            }
            // if(!empty($rc[$prop][0])){
                // return $rc[$prop][0];
            // }
        }
        return False;

    }

    private function getTemplateForProp($item, $prop)
    {

        $properties = $item->resourceTemplate()->resourceTemplateProperties();
        foreach($properties as $p){
            if($p->property()->term() == $prop){
                return $p;
            }
            $rc[] = $p->property()->term();
        }
        return Null;

    }

}
