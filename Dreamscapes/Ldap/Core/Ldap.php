<?php

/**
 * Dreamscapes/Ldap-Core
 *
 * Licensed under the BSD (3-Clause) license
 * For full copyright and license information, please see the LICENSE file
 *
 * @author      Robert Rossmann <rr.rossmann@me.com>
 * @copyright   2014 Robert Rossmann
 * @link        https://github.com/Dreamscapes/Ldap-Core
 * @license     http://choosealicense.com/licenses/bsd-3-clause   BSD (3-Clause) License
 */


namespace Dreamscapes\Ldap\Core;

use Dreamscapes\Ldap\LdapException;

// Backwards-compatibility for ldap_escape (available since PHP 5.6)

if (! defined('LDAP_ESCAPE_FILTER')) {
    /** @internal */
    define('LDAP_ESCAPE_FILTER', 0x1);
}

if (! defined('LDAP_ESCAPE_DN')) {
    /** @internal */
    define('LDAP_ESCAPE_DN', 0x2);
}

// LDAP-SASL constants (not present if ldap-sasl module is not installed)

if (! defined('LDAP_OPT_X_SASL_MECH')) {
    /** @internal */
    define('LDAP_OPT_X_SASL_MECH', 0x6100);
}

if (! defined('LDAP_OPT_X_SASL_REALM')) {
    /** @internal */
    define('LDAP_OPT_X_SASL_REALM', 0x6101);
}

if (! defined('LDAP_OPT_X_SASL_AUTHCID')) {
    /** @internal */
    define('LDAP_OPT_X_SASL_AUTHCID', 0x6102);
}

if (! defined('LDAP_OPT_X_SASL_AUTHZID')) {
    /** @internal */
    define('LDAP_OPT_X_SASL_AUTHZID', 0x6103);
}

// Backwards-compatibility for ldap_modify_batch (available in PHP ~5.4.26, >=5.5.10)

if (! defined('LDAP_MODIFY_BATCH_ADD')) {
    /** @internal */
    define('LDAP_MODIFY_BATCH_ADD', 0x1);
}

if (! defined('LDAP_MODIFY_BATCH_REMOVE')) {
    /** @internal */
    define('LDAP_MODIFY_BATCH_REMOVE', 0x2);
}

if (! defined('LDAP_MODIFY_BATCH_REPLACE')) {
    /** @internal */
    define('LDAP_MODIFY_BATCH_REPLACE', 0x3);
}

if (! defined('LDAP_MODIFY_BATCH_REMOVE_ALL')) {
    /** @internal */
    define('LDAP_MODIFY_BATCH_REMOVE_ALL', 0x12);
}


/**
 * Object encapsulation of the resource(ldap link) native object
 *
 * @method  Result read()   Perform search operation with SCOPE_BASE - see self::ldapSearch()
 *                          for argument list
 * @method  Result list()   Perform search operation with SCOPE_ONELEVEL - see
 *                          self::ldapSearch() for argument list
 * @method  Result search() Perform search operation with SCOPE_SUBTREE - see
 *                          self::ldapSearch() for argument list
 *
 * @package Ldap-Core
 */
class Ldap
{
    // LDAP RESPONSE CODES
    const SUCCESS                               = 0;
    const OPERATIONS_ERROR                      = 1;
    const PROTOCOL_ERROR                        = 2;
    const TIMELIMIT_EXCEEDED                    = 3;
    const SIZELIMIT_EXCEEDED                    = 4;
    const COMPARE_FALSE                         = 5;
    const COMPARE_TRUE                          = 6;
    const AUTH_METHOD_NOT_SUPPORTED             = 7;
    const STRONG_AUTH_REQUIRED                  = 8;
    const REFERRAL                              = 10;
    const ADMINLIMIT_EXCEEDED                   = 11;
    const UNAVAILABLE_CRITICAL_EXTENSION        = 12;
    const CONFIDENTIALITY_REQUIRED              = 13;
    const SASL_BIND_IN_PROGRESS                 = 14;
    const NO_SUCH_ATTRIBUTE                     = 16;
    const UNDEFINED_TYPE                        = 17;
    const INAPPROPRIATE_MATCHING                = 18;
    const CONSTRAINT_VIOLATION                  = 19;
    const TYPE_OR_VALUE_EXISTS                  = 20;
    const INVALID_SYNTAX                        = 21;
    const NO_SUCH_OBJECT                        = 32;
    const ALIAS_PROBLEM                         = 33;
    const INVALID_DN_SYNTAX                     = 34;
    const IS_LEAF                               = 35;
    const ALIAS_DEREF_PROBLEM                   = 36;
    const INAPPROPRIATE_AUTH                    = 48;
    const INVALID_CREDENTIALS                   = 49;
    const ERROR_TOO_MANY_CONTEXT_IDS            = 49;
    const INSUFFICIENT_ACCESS                   = 50;
    const BUSY                                  = 51;
    const UNAVAILABLE                           = 52;
    const UNWILLING_TO_PERFORM                  = 53;
    const LOOP_DETECT                           = 54;
    const NAMING_VIOLATION                      = 64;
    const OBJECT_CLASS_VIOLATION                = 65;
    const NOT_ALLOWED_ON_NONLEAF                = 66;
    const NOT_ALLOWED_ON_RDN                    = 67;
    const ALREADY_EXISTS                        = 68;
    const NO_OBJECT_CLASS_MODS                  = 69;
    const RESULTS_TOO_LARGE                     = 70;
    const AFFECTS_MULTIPLE_DSAS                 = 71;
    const OTHER                                 = 80;
    // Active Directory specific error codes
    const USER_NOT_FOUND                        = 525;
    const NOT_PERMITTED_TO_LOGON_AT_THIS_TIME   = 530;
    const RESTRICTED_TO_SPECIFIC_MACHINES       = 531;
    const PASSWORD_EXPIRED                      = 532;
    const ACCOUNT_DISABLED                      = 533;
    const ACCOUNT_EXPIRED                       = 701;
    const USER_MUST_RESET_PASSWORD              = 773;
    const USER_ACCOUNT_LOCKED                   = 775;

    // LDAP SEARCH SCOPES
    const SCOPE_BASE                            = 'ldap_read';
    const SCOPE_ONELEVEL                        = 'ldap_list';
    const SCOPE_SUBTREE                         = 'ldap_search';

    // LDAP OPTIONS
    const OPT_DEREF                             = LDAP_OPT_DEREF;
    const OPT_SIZELIMIT                         = LDAP_OPT_SIZELIMIT;
    const OPT_TIMELIMIT                         = LDAP_OPT_TIMELIMIT;
    const OPT_NETWORK_TIMEOUT                   = LDAP_OPT_NETWORK_TIMEOUT;
    const OPT_PROTOCOL_VERSION                  = LDAP_OPT_PROTOCOL_VERSION;
    const OPT_ERROR_NUMBER                      = LDAP_OPT_ERROR_NUMBER;
    const OPT_REFERRALS                         = LDAP_OPT_REFERRALS;
    const OPT_RESTART                           = LDAP_OPT_RESTART;
    const OPT_HOST_NAME                         = LDAP_OPT_HOST_NAME;
    const OPT_ERROR_STRING                      = LDAP_OPT_ERROR_STRING;
    const OPT_MATCHED_DN                        = LDAP_OPT_MATCHED_DN;
    const OPT_SERVER_CONTROLS                   = LDAP_OPT_SERVER_CONTROLS;
    const OPT_CLIENT_CONTROLS                   = LDAP_OPT_CLIENT_CONTROLS;
    const OPT_DEBUG_LEVEL                       = LDAP_OPT_DEBUG_LEVEL;

    // Available constants if ldap-sasl is present
    const OPT_X_SASL_MECH                       = LDAP_OPT_X_SASL_MECH;
    const OPT_X_SASL_REALM                      = LDAP_OPT_X_SASL_REALM;
    const OPT_X_SASL_AUTHCID                    = LDAP_OPT_X_SASL_AUTHCID;
    const OPT_X_SASL_AUTHZID                    = LDAP_OPT_X_SASL_AUTHZID;

    // MODIFY OPERATIONS (for self::modifyBatch())
    const MODIFY_BATCH_ADD                      = LDAP_MODIFY_BATCH_ADD;
    const MODIFY_BATCH_REMOVE                   = LDAP_MODIFY_BATCH_REMOVE;
    const MODIFY_BATCH_REMOVE_ALL               = LDAP_MODIFY_BATCH_REMOVE_ALL;
    const MODIFY_BATCH_REPLACE                  = LDAP_MODIFY_BATCH_REPLACE;

    // Escaping options (available since PHP 5.6)
    const ESCAPE_FILTER                         = LDAP_ESCAPE_FILTER;
    const ESCAPE_DN                             = LDAP_ESCAPE_DN;


    /**
     * PHP's native ldap resource object
     * @var resource (ldap link)
     */
    protected $resource;

    /**
     * @var integer The status code of the last executed ldap operation
     */
    protected $code;

    /**
     * @var string The status message of the last executed ldap operation
     */
    protected $message;


    /**
     * Convert DN to User Friendly Naming format
     *
     * @param  string $dn The distinguished name of an LDAP entity
     * @return string     The user friendly name
     */
    public static function dnToUfn($dn)
    {
        return ldap_dn2ufn($dn);
    }

    /**
     * Convert LDAP error number into string error message
     *
     * @param  integer $errno The error number
     * @return string         The error message, as a string
     */
    public static function errToStr($errno)
    {
        return ldap_err2str($errno);
    }

    /**
     * Splits DN into its component parts
     *
     * @param  string  $dn          The distinguished name of an LDAP entity
     * @param  integer $withAttrib  Used to request if the RDNs are returned with only values or
     *                              their attributes as well
     * @return array                Returns an array of all DN components
     */
    public static function explodeDn($dn, $withAttrib = 0)
    {
        return ldap_explode_dn($dn, $withAttrib);
    }

    /**
     * Escape a string for use in an LDAP filter or DN
     *
     * `$flags` may be one of: `Ldap::ESCAPE_FILTER`, `Ldap::ESCAPE_DN`
     *
     * @since  PHP 5.6
     * @param  string   $value  The value to escape
     * @param  string   $ignore Characters to ignore when escaping
     * @param  integer  $flags  The context the escaped string will be used in
     * @return string           Returns the escaped string
     */
    public static function escape($value, $ignore = null, $flags = null)
    {
        if (! function_exists('ldap_escape')) {
            // Bail out, can't work our magic!
            trigger_error('ldap_escape() is only available in PHP 5.6 or newer', E_USER_ERROR);
        }

        return ldap_escape($value, $ignore, $flags);
    }


    /**
     * Create a new instance
     *
     * If $ldapUrl is provided, it will also open connection to the ldap server by calling
     * self::connect().
     *
     * @param string $ldapUrl Optional ldap URI string of the ldap server
     */
    public function __construct($ldapUrl = null)
    {
        $ldapUrl && $this->connect($ldapUrl);
    }

    /**
     * Allow resource's string representation to be ldap URI string
     *
     * @return string Ldap server and port of this connection (i.e. example.com:389)
     */
    public function __toString()
    {
        return $this->getOption(static::OPT_HOST_NAME);
    }

    /**
     * Get the PHP's native ldap resource object
     *
     * @return resource (ldap link)
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Add entries to LDAP directory
     *
     * @param string $dn    The distinguished name of an LDAP entity
     * @param array  $entry An array that specifies the information about the entry
     * @return self
     */
    public function add($dn, array $entry)
    {
        @ldap_add($this->resource, $dn, $entry);
        $this->verifyOperation();

        return $this;
    }

    /** Alias of self::unbind() */
    public function close()
    {
        return $this->unbind();
    }

    /**
     * Compare value of attribute found in entry specified with DN
     *
     * @param  string   $dn         The distinguished name of an LDAP entity
     * @param  string   $attribute  The attribute name
     * @param  string   $value      The compared value
     * @return bool                 Returns TRUE if value matches otherwise returns FALSE
     */
    public function compare($dn, $attribute, $value)
    {
        $retVal = @ldap_compare($this->resource, $dn, $attribute, $value);
        $this->verifyOperation();

        return $retVal;
    }

    /**
     * Delete an entry from a directory
     *
     * @param  string $dn The distinguished name of an LDAP entity
     * @return self
     */
    public function delete($dn)
    {
        @ldap_delete($this->resource, $dn);
        $this->verifyOperation();

        return $this;
    }

    /**
     * Open connection to an ldap server
     *
     * @param  string       $ldapUrl    Ldap URI string of the ldap server (i.e.
     *                                  ldap://my.server.com:389)
     * @return self
     */
    public function connect($ldapUrl)
    {
        // Make sure the connection has been established successfully
        if (! $this->resource = @ldap_connect($ldapUrl)) {
            throw new \Exception(sprintf("Unable to connect to ldap server %s", $ldapUrl));
        }

        // Set sane defaults for ldap v3 protocol
        $this->setOption(LDAP_OPT_PROTOCOL_VERSION, 3)
             ->setOption(LDAP_OPT_REFERRALS, 0);

        return $this;
    }

    /**
     * Send LDAP pagination control
     *
     * @param  integer      $pageSize       The number of entries by page
     * @param  boolean      $isCritical     Indicates whether the pagination is critical of not
     * @param  string       $cookie         An opaque structure sent by the server
     * @return self
     */
    public function pagedResult($pageSize, $isCritical = false, $cookie = '')
    {
        ldap_control_paged_result($this->resource, $pageSize, $isCritical, $cookie);

        return $this;
    }

    /**
     * Bind to LDAP directory
     *
     * @param  string $bindDn       Username for the bind
     * @param  string $bindPassword Password for the username
     * @return self
     */
    public function bind($bindDn = null, $bindPassword = null)
    {
        @ldap_bind($this->resource, $bindDn, $bindPassword);
        $this->verifyOperation();

        return $this;
    }

    /**
     * Return the LDAP error message of the last LDAP command
     *
     * @return string
     */
    public function error()
    {
        return $this->message;
    }

    /**
     * Return the LDAP error number of the last LDAP command
     *
     * @return integer
     */
    public function errno()
    {
        return $this->code;
    }

    /**
     * Get the current value for given option
     *
     * @param  integer $option The option to be returned
     * @return mixed
     */
    public function getOption($option)
    {
        $retVal = null;
        ldap_get_option($this->resource, $option, $retVal);

        return $retVal;
    }

    /**
     * Add attribute values to current attributes
     *
     * @param  string $dn    The distinguished name of an LDAP entity
     * @param  array  $entry Values to be added to the specified attributes
     * @return self
     */
    public function modAdd($dn, array $entry)
    {
        @ldap_mod_add($this->resource, $dn, $entry);
        $this->verifyOperation();

        return $this;
    }

    /**
     * Delete attribute values from current attributes
     *
     * @param  string $dn    The distinguished name of an LDAP entity
     * @param  array  $entry Values to be deleted from the specified attributes
     * @return self
     */
    public function modDelete($dn, array $entry)
    {
        @ldap_mod_del($this->resource, $dn, $entry);
        $this->verifyOperation();

        return $this;
    }

    /**
     * Compatibility alias for self::modDelete()
     *
     * @param  string $dn    The distinguished name of an LDAP entity
     * @param  array  $entry Values to be deleted from the specified attributes
     * @return self
     */
    public function modDel($dn, array $entry)
    {
        return $this->modDelete($dn, $entry);
    }

    /**
     * Replace attribute values with new ones
     *
     * @param  string $dn    The distinguished name of an LDAP entity
     * @param  array  $entry Attributes and their values to be replaced
     * @return self
     */
    public function modReplace($dn, array $entry)
    {
        @ldap_mod_replace($this->resource, $dn, $entry);
        $this->verifyOperation();

        return $this;
    }

    /**
     * Modify an LDAP entry
     *
     * @param  string $dn    The distinguished name of an LDAP entity
     * @param  array  $entry Attributes with their modified values
     * @return self
     */
    public function modify($dn, array $entry)
    {
        @ldap_modify($this->resource, $dn, $entry);
        $this->verifyOperation();

        return $this;
    }

    /**
     * Modify an existing entry in the LDAP directory
     *
     * Allows detailed specification of the modifications to perform.
     *
     * Example:
     *
     * $modifs = array(
     *     array(
     *         "attrib"  => "telephoneNumber",
     *         "modtype" => Ldap::MODIFY_BATCH_ADD,
     *         "values"  => array("+420 777 111 222")
     *     )
     * );
     * $ldap->modifyBatch("cn=Robert Rossmann,dc=example,dc=com", $modifs);
     *
     * @since  PHP ~5.4.26, >=5.5.10
     * @param  string $dn    The distinguished name of an LDAP entity
     * @param  array  $entry Modification specifications
     * @return self
     *
     * @see https://wiki.php.net/rfc/ldap_modify_batch
     */
    public function modifyBatch($dn, array $entry)
    {
        if (! function_exists('ldap_modify_batch')) {
            // Bail out, can't work our magic!
            trigger_error(
                'ldap_modify_batch() is only available in PHP ~5.4.26 or >=5.5.10',
                E_USER_ERROR
            );
        }

        @ldap_modify_batch($this->resource, $dn, $entry);
        $this->verifyOperation();

        return $this;
    }

    /**
     * Modify the name of an entry
     *
     * @param  string $dn           The distinguished name of an LDAP entity
     * @param  string $newRdn       The new RDN
     * @param  string $newParent    The new parent/superior entry
     * @param  bool   $deleteOldRdn If TRUE the old RDN value(s) are removed, else the old RDN
     *                              value(s) are retained as non-distinguished values of the entry
     * @return self
     */
    public function rename($dn, $newRdn, $newParent, $deleteOldRdn)
    {
        @ldap_rename($this->resource, $dn, $newrdn, $newparent, $deleteoldrdn);
        $this->verifyOperation();

        return $this;
    }

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
    ) {
        @ldap_sasl_bind(
            $this->resource,
            $bindDn,
            $bindPassword,
            $saslMech,
            $saslRealm,
            $saslAuthcId,
            $saslAuthzId,
            $props
        );
        $this->verifyOperation();

        return $this;
    }

    /**
     * Search LDAP tree
     *
     * The scope of the operation is controlled by the $scope parameter, which can be one of:
     *
     * self::SCOPE_SUBTREE - equivalent of ldap_search() (default)
     * self::SCOPE_ONELEVEL - equivalent of ldap_list()
     * self::SCOPE_BASE - equivalent of ldap_read()
     *
     * @param  string  $baseDn      The base DN for the directory
     * @param  string  $filter      Ldap query filter (an empty filter is not allowed)
     * @param  array   $attributes  An array of the required attributes, e.g. array("mail", "sn",
     *                              "cn")
     * @param  string  $scope       One of self::SCOPE_SUBTREE, self::SCOPE_ONELEVEL or
     *                              self::SCOPE_BASE
     * @param  boolean $attrsOnly   Should be set to 1 if only attribute types are wanted
     * @param  integer $sizeLimit   Enables you to limit the count of entries fetched. Setting this
     *                              to 0 means no limit
     * @param  integer $timeLimit   Sets the number of seconds how long is spend on the search.
     *                              Setting this to 0 means no limit.
     * @param  integer $deref       Specifies how aliases should be handled during the search
     * @return Result
     */
    public function ldapSearch(
        $baseDn,
        $filter,
        array $attributes,
        $scope = self::SCOPE_SUBTREE,
        $attrsOnly = false,
        $sizeLimit = 0,
        $timeLimit = 0,
        $deref = LDAP_DEREF_NEVER
    ) {
        $result = $scope(
            $this->resource,
            $baseDn,
            $filter,
            $attributes,
            $attrsOnly,
            $sizeLimit,
            $timeLimit,
            $deref
        );
        $this->verifyOperation();

        return new Result($this, $result);
    }

    /**
     * Set the value of the given option
     *
     * @param integer $option An lDAP option constant
     * @param mixed   $newVal The new value for the option
     */
    public function setOption($option, $newVal)
    {
        @ldap_set_option($this->resource, $option, $newVal);
        $this->verifyOperation();

        return $this;
    }

    /**
     * Set a callback function to do re-binds on referral chasing
     *
     * @param callable $callback
     * @return self
     */
    public function setRebindProcedure(callable $callback)
    {
        @ldap_set_rebind_proc($this->resource, $callback);
        $this->verifyOperation();

        return $this;
    }

    /**
     * Compatibility alias of self::setRebindProcedure()
     *
     * @param callable $callback
     * @return self
     */
    public function setRebindProc(callable $callback)
    {
        return $this->setRebindProcedure($callback);
    }

    /**
     * Start TLS
     *
     * @return self
     */
    public function startTls()
    {
        @ldap_start_tls($this->resource);
        $this->verifyOperation();

        return $this;
    }

    /**
     * Unbind from LDAP directory
     *
     * Once unbound the instance holding the link resource must not be used any further.
     *
     * @return void
     */
    public function unbind()
    {
        ldap_unbind($this->resource);
    }

    /**
     * Magic method to provide read(), list() and search() methods
     *
     * @param  string           $method Method name that was called
     * @param  array            $args   Arguments with which the method was called
     * @return Result
     */
    public function __call($method, $args)
    {
        $scopeMap = [
            'read' => static::SCOPE_BASE,
            'list' => static::SCOPE_ONELEVEL,
            'search' => static::SCOPE_SUBTREE,
        ];

        // Only the methods above are allowed to be called magically
        if (! in_array($method, array_keys($scopeMap))) {
            trigger_error(
                sprintf('Call to undefined method %s::%s()', __CLASS__, $method),
                E_USER_ERROR
            );
        }

        // Pad the args array to 3 elements. If this actually happens and the NULLs are inserted,
        // it will cause an error - missing required arguments. This is by design - the first 3
        // arguments to ldap_search are mandatory. With this hat trick, we do not have to throw that
        // exception ourselves, letting PHP do the job.
        $args = array_pad($args, 3, null);
        // Append the search scope to the argument list at key 3 (fourth arg)
        array_splice($args, 3, 0, $scopeMap[$method]);

        // Do the actual search
        return call_user_func_array([$this, 'ldapSearch'], $args);
    }


    /**
     * Check the ldap status code and throw exception on error
     *
     * @return void
     */
    protected function verifyOperation()
    {
        $this->code = ldap_errno($this->resource);
        $this->message = ldap_error($this->resource);

        // Active Directory conceals some additional error codes in the ErrorMessage of the response
        // that we cannot get to with ldap_errno() in authentication failures - let's try to extract
        // them!
        if ($this->code === 49) {
            $errorString = $this->getOption(static::OPT_ERROR_STRING);

            // "LDAP: error code 49 - 80090308: LdapErr: DSID-0C090334, comment:
            // AcceptSecurityContext error, data 775, vece"
            //                                   ^^^
            // Note for my future self - the code '52e' will not be matched. But that's alright -
            // you would have replaced it with '49' anyway.
            preg_match('/(?<=data )[0-9]{2,3}/', $errorString, $matches);

            // Have we found it?
            if (count($matches) === 1) {
                $this->code = $matches[0];
            }
        }

        switch ($this->code) {
            // These response codes do not represent a failed operation; everything else does
            case static::SUCCESS:
            case static::SIZELIMIT_EXCEEDED:
            case static::COMPARE_FALSE:
            case static::COMPARE_TRUE:

                break;

            default:
                throw new LdapException($this);
        }
    }
}
