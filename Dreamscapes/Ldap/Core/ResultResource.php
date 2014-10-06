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

/**
 * Object encapsulation of the resource(ldap result) native object
 *
 * @package Ldap-Core
 */
class ResultResource
{
    /**
     * PHP's native ldap link resource object
     * @var resource (ldap link)
     */
    protected $link;

    /**
     * PHP's native ldap resource object
     * @var resource (ldap result)
     */
    protected $resource;


    /**
     * Create a new instance
     *
     * @param LinkResourceInterface $link
     * @param resource              $result
     */
    public function __construct(LinkResource $link, $result)
    {
        $this->link = $link->getResource();
        $this->resource = $result;
    }

    /**
     * Retrieve the LDAP pagination cookie
     *
     * @return array    Array with two keys: 'cookie' and 'estimated'
     */
    public function pagedResultResponse()
    {
        $cookie = null;
        $estimated = null;

        @ldap_control_paged_result_response($this->link, $this->resource, $cookie, $estimated);

        return [
            'cookie' => $cookie,
            'estimated' => $estimated,
        ];
    }

    /**
     * Count the number of entries in a search
     *
     * @return int      Number of entries in the result
     */
    public function countEntries()
    {
        return ldap_count_entries($this->link, $this->resource);
    }

    /**
     * Free result memory
     *
     * Once the result is freed from memory the instance holding the result must not be used any further. This method
     * is called automatically when the instance is released from memory.
     *
     * @return void
     */
    public function freeResult()
    {
        ldap_free_result($this->resource);
    }

    /**
     * Get all result entries
     *
     * @return array    Complete result information in a multi-dimensional array
     */
    public function getEntries()
    {
        return ldap_get_entries($this->link, $this->resource);
    }

    /**
     * Extract information about referrals (if returned by server)
     *
     * @return array
     */
    public function getReferrals()
    {
        $referrals = null;

        ldap_parse_reference($this->link, $this->resource, $referrals);

        return $referrals;
    }

    /**
     * Sort LDAP result entries
     *
     * @param  string $sortFilter The attribute to use as a key in the sort
     * @return self
     */
    public function sort($sortFilter)
    {
        ldap_sort($this->link, $this->resource, $sortFilter);

        return $this;
    }

    /**
     * Release the instance from memory
     *
     * @internal
     */
    public function __destruct()
    {
        $this->freeResult();
    }
}
