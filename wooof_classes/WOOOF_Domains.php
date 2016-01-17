<?php 

// Data Domains' functions

class WOOOF_Domains {
    const _ECP = 'WDO';	// Error Code Prefix        

    /***************************************************************************/
    /***************************************************************************/

    /**
    *
    * $outputType : 'array', 'JSON', 'select', 'radio'
    *
    *
    */

    public static function getMultipleDomains(WOOOF $wo, array $domains, $outputType = 'array')
    {
        $out = array();
        foreach($domains as $domain)
        {
            if (is_array($domain))
            {
                $out[$domain[0]] = self::getDomainValues($wo, $domain[0], $domain[1]);
                if (!is_array($out[$domain[0]]))
                {
                    return FALSE;
                }
            }else
            {
                $out[$domain] = self::getDomainValues($wo, $domain);
                if (!is_array($out[$domain]))
                {
                    return FALSE;
                }
            }
        }
        
        if ($outputType=='JSON')
        {
            $out = json_encode($out);
        }
        
        return $out;
    }
    
    /***************************************************************************/
    /***************************************************************************/

    /**
     * getDomainValues get the data values for a requested domain/subdomain
     * 
     * @param WOOOD $wo -- the initialized instance of WOOOF to use
     * @param string $requestedDomain  -- the domain whose values we want to retrieve
     * @param string $requestedSubDomain -- optional -- if the domain is split in subdomains, the requested subdomain should be specified here. If there are subdomains in the domain and no subdomain is specified all the values will be returned regardless of subdomain bu a warning will be written to the debug log. 
     * 
     * @return array An array that contains the values for the specified domin in the requested format(s)
     */

    public static function getDomainValues(WOOOF $wo, $requestedDomain, $requestedSubDomain = '')
    {
        $domain = $wo->db->getRowByColumn('__domains', 'code', $requestedDomain);

        if ($domain===FALSE)
        {
            return FALSE;
        }elseif (!isset($domain['id']))
        {
            $wo->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP.'0201 Requested domain ['. $wo->cleanUserInput($requestedDomain) .'] doesn\'t exist in the database!');
            return FALSE;
        }
        
        $domainData = new WOOOF_dataBaseTable($wo->db, '__domain_values');
        
        if (!$domainData->constructedOk)
        {
            $wo->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP.'0202 Internal failure. Failed to construct instance of __domain_values!');
            return FALSE;
        }
        
        $whereClauses['domainId'] = $domain['id'];
        if (!$wo->hasContent($requestedSubDomain))
        {
            $subDomainsR = $wo->db->query('select distinct subDomain from __domain_values where domainId = \''. $wo->db->escape($domain['id']) .'\'');
            if ($wo->db->getNumRows($subDomainsR)>1)
            {
                $wo->log(WOOOF_loggingLevels::WOOOF_WARNING,  self::_ECP.'0203 Requested domain has subdomains but none was supplied. All domain values will be returned, but this might not be the intended data.');
            }
        }else
        {
            $subDomainsR = $wo->db->query('select subDomain from __domain_values where domainId = \''. $wo->db->escape($domain['id']) .'\' and subDomain = \''. $wo->db->escape($requestedSubDomain) .'\'');
            if (!$wo->db->getNumRows($subDomainsR))
            {
                $wo->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP.'0204 Requested subdomain ['. $wo->db->cleanUserInput($requestedSubDomain) .'] doesn\'t exist in the database!');
                return FALSE;
            }
            $whereClauses['subDomain'] = $requestedSubDomain;
        }
        
        $whereClauses['active'] = '1';
        
        $out = array();
        
        if ($domainData->getResult($whereClauses, 'ord', '', '', '', TRUE, FALSE)===FALSE)
        {
            $wo->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP.'0205 Operation failed in result retrieval from domain values.');
            return FALSE;
        }
        
        foreach ($domainData->resultRows as $row)
        { 
             $out[] = array('value' => $row['id'], 'code' => $row['domainValueCode'], 'label' => $row['description'], 'comments' => $row['comments'], 'picture' => $row['picture'], 'iconFont' => $row['iconFont'], 'extraInfo1' => $row['extraInfo1']);
        }
        
        return $out;
    }
        
    /***************************************************************************/
    /***************************************************************************/

    /**
     * getDomainValues get the data values for a requested domain/subdomain
     * 
     * @param WOOOD $wo -- the initialized instance of WOOOF to use
     * @param string $value  -- the value to check
     * @param string $requestedDomain  -- the domain whose values we want to retrieve
     * @param string $requestedSubDomain -- optional -- if the domain is split in subdomains, the requested subdomain should be specified here. If there are subdomains in the domain and no subdomain is specified all the values will be returned regardless of subdomain bu a warning will be written to the debug log. 
     * @param string $mandatory -- optional -- if the given value must have content or not
     * 
     * @return boolean TRUE for valid input.
     */

    public static function validateId(WOOOF $wo, $value, $requestedDomain, $requestedSubDomain = '', $mandatory = TRUE)
    {
        if (!$wo->hasContent($value) && $mandatory)
        {
            return FALSE;
        }
        
        $domain = $wo->db->getRowByColumn('__domains', 'code', $requestedDomain);
        if ($domain===FALSE)
        {
            return FALSE;
        }elseif (!isset($domain['id']))
        {
            $wo->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP.'0301 Requested domain ['. $wo->cleanUserInput($requestedDomain) .'] doesn\'t exist in the database!');
            return FALSE;
        }
        
        $domainData = new WOOOF_dataBaseTable($wo->db, '__domain_values');
        if (!$domainData->constructedOk)
        {
            $wo->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP.'0302 Internal failure. Failed to construct instance of __domain_values!');
            return FALSE;
        }
        
        $whereClauses['domainId'] = $domain['id'];
        if ($wo->hasContent($requestedSubDomain))
        {
            $subDomainsR = $wo->db->query('select subDomain from __domain_values where domainId = \''. $wo->db->escape($domain['id']) .'\' and subDomain = \''. $wo->db->escape($requestedSubDomain) .'\'');
            if (!$wo->db->getNumRows($subDomainsR))
            {
                $wo->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP.'0204 Requested subdomain ['. $wo->cleanUserInput($requestedSubDomain) .'] doesn\'t exist in the database!');
                return FALSE;
            }
            $whereClauses['subDomain'] = $requestedSubDomain;
        }
        $whereClauses['active'] = '1';
        $whereClauses['domainValueCode'] = $value;
        
        $howManyResults = $domainData->getResult($whereClauses);
        
        if ($howManyResults===FALSE)
        {
            $wo->log(WOOOF_loggingLevels::WOOOF_ERROR, self::_ECP.'0303 Operation failed in result retrieval from domain values.');
            return FALSE;
        }
        
        if ($howManyResults['rowsFetched']==0)
        {
            return FALSE;
        }
        
        return TRUE;
    }
}