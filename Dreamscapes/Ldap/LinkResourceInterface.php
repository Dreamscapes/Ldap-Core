<?php

/**
 * Dreamscapes/Ldap-Core
 *
 * Licensed under the BSD (3-Clause) license
 * For full copyright and license information, please see the LICENSE file
 *
 * @author      Robert Rossmann <rr.rossmann@me.com>
 * @copyright   2013 Robert Rossmann
 * @link        https://github.com/Dreamscapes/Ldap-Core
 * @license     http://choosealicense.com/licenses/bsd-3-clause   BSD (3-Clause) License
 */


namespace Dreamscapes\Ldap;

/**
 * This interface prescribes which methods a class must implement to serve as object encapsulation of PHP's native ldap
 * link resource and related functions.
 *
 * @package Ldap-Core
 */
interface LinkResourceInterface
{
    /**
     * Create a new instance
     *
     * If $ldapUrl is provided, it should also open connection to the ldap server by calling self::connect()
     *
     * @param string $ldapUrl Optional ldap URI string of the ldap server
     */
    public function __construct($ldapUrl = null);

    /**
     * Convert DN to User Friendly Naming format
     *
     * @param  string $dn The distinguished name of an LDAP entity
     * @return string     The user friendly name
     */
    public static function dnToUfn($dn);

    /**
     * Convert LDAP error number into string error message
     *
     * @param  integer $errno The error number
     * @return string         The error message, as a string
     */
    public static function errToStr($errno);

    /**
     * Splits DN into its component parts
     *
     * @param  string  $dn         The distinguished name of an LDAP entity
     * @param  integer $withAttrib Used to request if the RDNs are returned with only values or their attributes as well
     * @return array               Returns an array of all DN components
     */
    public static function explodeDn($dn, $withAttrib = 0);


    /**
     * Get the PHP's native ldap resource object
     *
     * @return resource (ldap link)
     */
    public function getResource();

    /**
     * Add entries to LDAP directory
     *
     * @param string $dn    The distinguished name of an LDAP entity
     * @param array  $entry An array that specifies the information about the entry
     * @return self
     */
    public function add($dn, array $entry);

    /**
     * Compare value of attribute found in entry specified with DN
     *
     * @param  string   $dn         The distinguished name of an LDAP entity
     * @param  string   $attribute  The attribute name
     * @param  string   $value      The compared value
     * @return bool                 Returns TRUE if value matches otherwise returns FALSE
     */
    public function compare($dn, $attribute, $value);

    /**
     * Delete an entry from a directory
     *
     * @param  string $dn The distinguished name of an LDAP entity
     * @return self
     */
    public function delete($dn);

    /**
     * Open connection to an ldap server
     *
     * @param  string       $ldapUrl    Ldap URI string of the ldap server (i.e. ldap://my.server.com:389)
     * @return self
     */
    public function connect($ldapUrl);

    /**
     * Send LDAP pagination control
     *
     * @param  integer      $pageSize       The number of entries by page
     * @param  boolean      $isCritical     Indicates whether the pagination is critical of not
     * @param  string       $cookie         An opaque structure sent by the server
     * @return self
     */
    public function pagedResult($pageSize, $isCritical = false, $cookie = '');

    /**
     * Bind to LDAP directory
     *
     * @param  string $bindDn       Username for the bind
     * @param  string $bindPassword Password for the username
     * @return self
     */
    public function bind($bindDn = null, $bindPassword = null);

    /**
     * Return the LDAP error message of the last LDAP command
     *
     * @return string
     */
    public function error();

    /**
     * Return the LDAP error number of the last LDAP command
     *
     * @return integer
     */
    public function errno();

    /**
     * Get the current value for given option
     *
     * @param  integer $option The option to be returned
     * @return mixed
     */
    public function getOption($option);

    /**
     * Add attribute values to current attributes
     *
     * @param  string $dn    The distinguished name of an LDAP entity
     * @param  array  $entry Values to be added to the specified attributes
     * @return self
     */
    public function modAdd($dn, array $entry);

    /**
     * Delete attribute values from current attributes
     *
     * @param  string $dn    The distinguished name of an LDAP entity
     * @param  array  $entry Values to be deleted from the specified attributes
     * @return self
     */
    public function modDelete($dn, array $entry);

    /** Compatibility alias for self::modDelete() */
    public function modDel($dn, array $entry);

    /**
     * Replace attribute values with new ones
     *
     * @param  string $dn    The distinguished name of an LDAP entity
     * @param  array  $entry Attributes and their values to be replaced
     * @return self
     */
    public function modReplace($dn, array $entry);

    /**
     * Modify an LDAP entry
     *
     * @param  string $dn    The distinguished name of an LDAP entity
     * @param  array  $entry Attributes with their modified values
     * @return self
     */
    public function modify($dn, array $entry);

    /**
     * Modify the name of an entry
     *
     * @param  string $dn           The distinguished name of an LDAP entity
     * @param  string $newRdn       The new RDN
     * @param  string $newParent    The new parent/superior entry
     * @param  bool   $deleteOldRdn If TRUE the old RDN value(s) is removed, else the old RDN value(s) is retained as
     *                              non-distinguished values of the entry
     * @return self
     */
    public function rename($dn, $newRdn, $newParent, $deleteOldRdn);

    /**
     * Bind to LDAP directory using SASL
     *
     * @param  string $bindDn
     * @param  string $bindPassword
     * @param  string $saslMech
     * @param  string $saslRealm
     * @param  string $saslAuthcId
     * @param  string $saslAuthzId
     * @param  string $props
     * @return self
     */
    public function saslBind(
        $bindDn = null,
        $bindPassword = null,
        $saslMech = null,
        $saslRealm = null,
        $saslAuthcId = null,
        $saslAuthzId = null,
        $props = null
    );

    /**
     * Search LDAP tree
     *
     * @param  string  $baseDn     The base DN for the directory
     * @param  string  $filter     Ldap query filter ( an empty filter is not allowed )
     * @param  array   $attributes An array of the required attributes, e.g. array("mail", "sn", "cn")
     * @param  string  $scope      Subtree, one-level or base
     * @param  boolean $attrsOnly  Should be set to 1 if only attribute types are wanted
     * @param  integer $sizeLimit  Enables you to limit the count of entries fetched. Setting this to 0 means no limit
     * @param  integer $timeLimit  Sets the number of seconds how long is spend on the search.
     *                             Setting this to 0 means no limit.
     * @param  integer $deref      Specifies how aliases should be handled during the search
     * @return ResultResource
     */
    public function search(
        $baseDn,
        $filter,
        array $attributes,
        $scope = self::SCOPE_SUBTREE,
        $attrsOnly = false,
        $sizeLimit = 0,
        $timeLimit = 0,
        $deref = LDAP_DEREF_NEVER
    );

    /**
     * Set the value of the given option
     *
     * @param integer $option An lDAP option constant
     * @param mixed   $newVal The new value for the option
     */
    public function setOption($option, $newVal);

    /**
     * Set a callback function to do re-binds on referral chasing
     *
     * @param callable $callback
     * @return self
     */
    public function setRebindProcedure(callable $callback);

    /** Compatibility alias of self::setRebindProcedure() */
    public function setRebindProc(callable $callback);

    /**
     * Start TLS
     */
    public function startTls();

    /**
     * Unbind from LDAP directory
     */
    public function unbind();
}
