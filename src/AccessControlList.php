<?php
namespace PhpSimpleAcl;


class AccessControlList
{
    protected $privilegesByRole = array();
    protected $rolesByUser = array();
    

    // allow('admin','station','read')
    public function allow($role, $resource, $privilege)
    {
        if (!isset($this->privilegesByRole[$role]))
        {
            $this->privilegesByRole[$role] = array();  
        }
        if (!isset($this->privilegesByRole[$role][$resource]))
        {
            $this->privilegesByRole[$role][$resource] = array();
        }
        
        if (!is_array($privilege))
        {
            array_push($this->privilegesByRole[$role][$resource], $privilege); 
        }
        else 
        {
            $this->privilegesByRole[$role][$resource] = array_merge($this->privilegesByRole[$role][$resource], $privilege);
        }
    }
    
    protected function getPrivilegesForRole($role, $resource)
    {
        if(!isset($this->privilegesByRole[$role]))
        {
            return array();
        }
        else if (!isset($this->privilegesByRole[$role][$resource]))
        {
            return array();
        }
        else
        {
            return $this->privilegesByRole[$role][$resource];
        }
    }
    
    
    // assignRole('admin','u123456')    
    public function assignRole($role, $user)
    {
        if (!isset($this->rolesByUser[$user]))
        {
           $this->rolesByUser[$user] = array(); 
        }
        
        if (!is_array($role))
        {
            array_push($this->rolesByUser[$user], $role);
        }
        else
        {
            $this->rolesByUser[$user] = array_merge($this->rolesByUser[$user], $role);
        }
    }

    protected function getRolesForUser($user)
    {
        if (!isset($this->rolesByUser[$user]))
        {
            return array();
        }
        else
        {
            return $this->rolesByUser[$user];
        }
    }

    // isAllowed('u213475', 'station', 'read')
    public function isAllowed($user, $resource, $privilege)
    {
        foreach( $this->getRolesForUser($user) as $role)
        {
            if (array_search($privilege, $this->getPrivilegesForRole($role, $resource)) !== false)
            {
                return true;
            }
        }
        
        return false;
    }

}
