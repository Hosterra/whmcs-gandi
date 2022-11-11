<?php

// DNS RECORD TYPES SUPPORT

$allowed_recordtypes = [
	'standard' => [ 'A', 'AAAA', 'MXE', 'MX', 'CNAME', 'TXT', 'URL', 'FRAME' ],
	'extended' => [ 'A', 'AAAA', 'ALIAS', 'CAA', 'CNAME', 'DNAME', 'LOC', 'MX', 'NAPTR', 'OPENPGPKEY', 'PTR', 'RP', 'SPF', 'SRV', 'SSHFP', 'TLSA', 'TXT' ]
];