CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_almanac` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_binding` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `servicePHID` varbinary(64) NOT NULL,
  `devicePHID` varbinary(64) NOT NULL,
  `interfacePHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isDisabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_service` (`servicePHID`,`interfacePHID`),
  KEY `key_device` (`devicePHID`),
  KEY `key_interface` (`interfacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_bindingtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_device` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `nameIndex` binary(12) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `isBoundToClusterService` tinyint(1) NOT NULL,
  `status` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_name` (`nameIndex`),
  KEY `key_nametext` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_devicename_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_devicetransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_interface` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `devicePHID` varbinary(64) NOT NULL,
  `networkPHID` varbinary(64) NOT NULL,
  `address` varchar(64) NOT NULL,
  `port` int(10) unsigned NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_unique` (`devicePHID`,`networkPHID`,`address`,`port`),
  KEY `key_location` (`networkPHID`,`address`,`port`),
  KEY `key_device` (`devicePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_interfacetransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_namespace` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `nameIndex` binary(12) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_nameindex` (`nameIndex`),
  KEY `key_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_namespacename_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_namespacetransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_network` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(128) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_networkname_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_networktransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `fieldIndex` binary(12) NOT NULL,
  `fieldName` varchar(128) NOT NULL,
  `fieldValue` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `objectPHID` (`objectPHID`,`fieldIndex`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `nameIndex` binary(12) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `serviceType` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_name` (`nameIndex`),
  KEY `key_nametext` (`name`),
  KEY `key_servicetype` (`serviceType`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_servicename_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `almanac_servicetransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_almanac`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_application` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_application`;

CREATE TABLE `application_application` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_application`;

CREATE TABLE `application_applicationtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_application`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_application`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_audit` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_audit`;

CREATE TABLE `audit_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_audit`;

CREATE TABLE `audit_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `commitPHID` varbinary(64) DEFAULT NULL,
  `pathID` int(10) unsigned DEFAULT NULL,
  `isNewFile` tinyint(1) NOT NULL,
  `lineNumber` int(10) unsigned NOT NULL,
  `lineLength` int(10) unsigned NOT NULL,
  `fixedState` varchar(12) DEFAULT NULL,
  `hasReplies` tinyint(1) NOT NULL,
  `replyToCommentPHID` varbinary(64) DEFAULT NULL,
  `legacyCommentID` int(10) unsigned DEFAULT NULL,
  `attributes` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`),
  KEY `key_path` (`pathID`),
  KEY `key_draft` (`authorPHID`,`transactionPHID`),
  KEY `key_commit` (`commitPHID`),
  KEY `key_legacy` (`legacyCommentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_auth` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_challenge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `userPHID` varbinary(64) NOT NULL,
  `factorPHID` varbinary(64) NOT NULL,
  `sessionPHID` varbinary(64) NOT NULL,
  `challengeKey` varchar(255) NOT NULL,
  `challengeTTL` int(10) unsigned NOT NULL,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `workflowKey` varchar(255) NOT NULL,
  `responseDigest` varchar(255) DEFAULT NULL,
  `responseTTL` int(10) unsigned DEFAULT NULL,
  `isCompleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_issued` (`userPHID`,`challengeTTL`),
  KEY `key_collection` (`challengeTTL`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_contactnumber` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `contactNumber` varchar(255) NOT NULL,
  `status` varchar(32) NOT NULL,
  `properties` longtext NOT NULL,
  `uniqueKey` binary(12) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isPrimary` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_unique` (`uniqueKey`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_contactnumbertransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_factorconfig` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `userPHID` varbinary(64) NOT NULL,
  `factorName` longtext NOT NULL,
  `factorSecret` longtext NOT NULL,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `factorProviderPHID` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_user` (`userPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_factorprovider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `providerFactorKey` varchar(64) NOT NULL,
  `status` varchar(32) NOT NULL,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_factorprovidertransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_hmackey` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyName` varchar(64) NOT NULL,
  `keyValue` varchar(128) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`keyName`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `messageKey` varchar(64) NOT NULL,
  `messageText` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_type` (`messageKey`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_messagetransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_password` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `passwordType` varchar(64) NOT NULL,
  `passwordHash` varchar(128) NOT NULL,
  `isRevoked` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `passwordSalt` varchar(64) NOT NULL,
  `legacyDigestFormat` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_role` (`objectPHID`,`passwordType`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_passwordtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_providerconfig` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `providerClass` varchar(128) NOT NULL,
  `providerType` varchar(32) NOT NULL,
  `providerDomain` varchar(128) NOT NULL,
  `isEnabled` tinyint(1) NOT NULL,
  `shouldAllowLogin` tinyint(1) NOT NULL,
  `shouldAllowRegistration` tinyint(1) NOT NULL,
  `shouldAllowLink` tinyint(1) NOT NULL,
  `shouldAllowUnlink` tinyint(1) NOT NULL,
  `shouldTrustEmails` tinyint(1) NOT NULL DEFAULT 0,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `shouldAutoLogin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_provider` (`providerType`,`providerDomain`),
  KEY `key_class` (`providerClass`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_providerconfigtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_sshkey` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `keyType` varchar(255) NOT NULL,
  `keyBody` longtext NOT NULL,
  `keyComment` varchar(255) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `keyIndex` binary(12) NOT NULL,
  `isTrusted` tinyint(1) NOT NULL,
  `isActive` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_activeunique` (`keyIndex`,`isActive`),
  KEY `key_object` (`objectPHID`),
  KEY `key_active` (`isActive`,`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_sshkeytransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_auth`;

CREATE TABLE `auth_temporarytoken` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tokenResource` varbinary(64) NOT NULL,
  `tokenType` varchar(64) NOT NULL,
  `tokenExpires` int(10) unsigned NOT NULL,
  `tokenCode` varchar(64) NOT NULL,
  `userPHID` varbinary(64) DEFAULT NULL,
  `properties` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_token` (`tokenResource`,`tokenType`,`tokenCode`),
  KEY `key_expires` (`tokenExpires`),
  KEY `key_user` (`userPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_badges` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_badges`;

CREATE TABLE `badges_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `badgePHID` varbinary(64) NOT NULL,
  `recipientPHID` varbinary(64) NOT NULL,
  `awarderPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_badge` (`badgePHID`,`recipientPHID`),
  KEY `key_recipient` (`recipientPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_badges`;

CREATE TABLE `badges_badge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `flavor` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `icon` varchar(255) NOT NULL,
  `quality` int(10) unsigned NOT NULL,
  `status` varchar(32) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `creatorPHID` varbinary(64) NOT NULL,
  `mailKey` binary(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_creator` (`creatorPHID`,`dateModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_badges`;

CREATE TABLE `badges_badgename_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_badges`;

CREATE TABLE `badges_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_badges`;

CREATE TABLE `badges_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_badges`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_badges`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_cache` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_cache`;

CREATE TABLE `cache_general` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cacheKeyHash` binary(12) NOT NULL,
  `cacheKey` varchar(128) NOT NULL,
  `cacheFormat` varchar(16) NOT NULL,
  `cacheData` longblob NOT NULL,
  `cacheCreated` int(10) unsigned NOT NULL,
  `cacheExpires` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_cacheKeyHash` (`cacheKeyHash`),
  KEY `key_cacheCreated` (`cacheCreated`),
  KEY `key_ttl` (`cacheExpires`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_cache`;

CREATE TABLE `cache_markupcache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cacheKey` varchar(128) NOT NULL,
  `cacheData` longblob NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cacheKey` (`cacheKey`),
  KEY `dateCreated` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_calendar` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `hostPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `description` longtext NOT NULL,
  `isCancelled` tinyint(1) NOT NULL,
  `name` longtext NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `isAllDay` tinyint(1) NOT NULL,
  `icon` varchar(32) NOT NULL,
  `isRecurring` tinyint(1) NOT NULL,
  `instanceOfEventPHID` varbinary(64) DEFAULT NULL,
  `sequenceIndex` int(10) unsigned DEFAULT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  `isStub` tinyint(1) NOT NULL,
  `utcInitialEpoch` int(10) unsigned NOT NULL,
  `utcUntilEpoch` int(10) unsigned DEFAULT NULL,
  `utcInstanceEpoch` int(10) unsigned DEFAULT NULL,
  `parameters` longtext NOT NULL,
  `importAuthorPHID` varbinary(64) DEFAULT NULL,
  `importSourcePHID` varbinary(64) DEFAULT NULL,
  `importUIDIndex` binary(12) DEFAULT NULL,
  `importUID` longtext DEFAULT NULL,
  `seriesParentPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_instance` (`instanceOfEventPHID`,`sequenceIndex`),
  UNIQUE KEY `key_rdate` (`instanceOfEventPHID`,`utcInstanceEpoch`),
  KEY `key_epoch` (`utcInitialEpoch`,`utcUntilEpoch`),
  KEY `key_series` (`seriesParentPHID`,`utcInitialEpoch`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_event_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_event_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_event_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_event_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_eventinvitee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventPHID` varbinary(64) NOT NULL,
  `inviteePHID` varbinary(64) NOT NULL,
  `inviterPHID` varbinary(64) NOT NULL,
  `status` varchar(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `availability` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_event` (`eventPHID`,`inviteePHID`),
  KEY `key_invitee` (`inviteePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_eventtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_eventtransaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_export` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` longtext NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `policyMode` varchar(64) NOT NULL,
  `queryKey` varchar(64) NOT NULL,
  `secretKey` binary(20) NOT NULL,
  `isDisabled` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_secret` (`secretKey`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_author` (`authorPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_exporttransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_externalinvitee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` longtext NOT NULL,
  `nameIndex` binary(12) NOT NULL,
  `uri` longtext NOT NULL,
  `parameters` longtext NOT NULL,
  `sourcePHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`nameIndex`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_import` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` longtext NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `engineType` varchar(64) NOT NULL,
  `parameters` longtext NOT NULL,
  `isDisabled` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `triggerPHID` varbinary(64) DEFAULT NULL,
  `triggerFrequency` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_author` (`authorPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_importlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `importPHID` varbinary(64) NOT NULL,
  `parameters` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_import` (`importPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_importtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `calendar_notification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventPHID` varbinary(64) NOT NULL,
  `utcInitialEpoch` int(10) unsigned NOT NULL,
  `targetPHID` varbinary(64) NOT NULL,
  `didNotifyEpoch` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_notify` (`eventPHID`,`utcInitialEpoch`,`targetPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_calendar`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_conduit` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_conduit`;

CREATE TABLE `conduit_certificatetoken` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userPHID` varbinary(64) NOT NULL,
  `token` varchar(64) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userPHID` (`userPHID`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_conduit`;

CREATE TABLE `conduit_methodcalllog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connectionID` bigint(20) unsigned DEFAULT NULL,
  `method` varchar(64) NOT NULL,
  `error` varchar(255) NOT NULL,
  `duration` bigint(20) unsigned NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `callerPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key_method` (`method`),
  KEY `key_callermethod` (`callerPHID`,`method`),
  KEY `key_date` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_conduit`;

CREATE TABLE `conduit_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `tokenType` varchar(32) NOT NULL,
  `token` varchar(32) NOT NULL,
  `expires` int(10) unsigned DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `tokenName` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_token` (`token`),
  KEY `key_object` (`objectPHID`,`tokenType`),
  KEY `key_expires` (`expires`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_config` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_config`;

CREATE TABLE `config_entry` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `namespace` varchar(64) NOT NULL,
  `configKey` varchar(64) NOT NULL,
  `value` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_name` (`namespace`,`configKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_config`;

CREATE TABLE `config_manualactivity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activityType` varchar(64) NOT NULL,
  `parameters` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_type` (`activityType`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_config`;

CREATE TABLE `config_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `metadata` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_conpherence` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_conpherence`;

CREATE TABLE `conpherence_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `threadPHID` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) NOT NULL,
  `previousTransactionPHID` varbinary(64) DEFAULT NULL,
  `corpus` longtext CHARACTER SET {$CHARSET_FULLTEXT} COLLATE {$COLLATE_FULLTEXT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_transaction` (`transactionPHID`),
  UNIQUE KEY `key_previous` (`previousTransactionPHID`),
  KEY `key_thread` (`threadPHID`),
  FULLTEXT KEY `key_corpus` (`corpus`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_conpherence`;

CREATE TABLE `conpherence_participant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `participantPHID` varbinary(64) NOT NULL,
  `conpherencePHID` varbinary(64) NOT NULL,
  `seenMessageCount` bigint(20) unsigned NOT NULL,
  `settings` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conpherencePHID` (`conpherencePHID`,`participantPHID`),
  KEY `key_thread` (`participantPHID`,`conpherencePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_conpherence`;

CREATE TABLE `conpherence_thread` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `messageCount` bigint(20) unsigned NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `joinPolicy` varbinary(64) NOT NULL,
  `mailKey` varchar(20) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `topic` varchar(255) NOT NULL,
  `profileImagePHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_conpherence`;

CREATE TABLE `conpherence_threadtitle_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_conpherence`;

CREATE TABLE `conpherence_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `metadata` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_conpherence`;

CREATE TABLE `conpherence_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `conpherencePHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`),
  UNIQUE KEY `key_draft` (`authorPHID`,`conpherencePHID`,`transactionPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_conpherence`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_conpherence`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_countdown` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_countdown`;

CREATE TABLE `countdown` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `description` longtext NOT NULL,
  `mailKey` binary(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_epoch` (`epoch`),
  KEY `key_author` (`authorPHID`,`epoch`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_countdown`;

CREATE TABLE `countdown_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_countdown`;

CREATE TABLE `countdown_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_countdown`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_countdown`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_daemon` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_daemon`;

CREATE TABLE `daemon_locklog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lockName` varchar(64) NOT NULL,
  `lockReleased` int(10) unsigned DEFAULT NULL,
  `lockParameters` longtext NOT NULL,
  `lockContext` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_lock` (`lockName`),
  KEY `key_created` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_daemon`;

CREATE TABLE `daemon_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `daemon` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `argv` longtext NOT NULL,
  `explicitArgv` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `status` varchar(8) NOT NULL,
  `runningAsUser` varchar(255) DEFAULT NULL,
  `daemonID` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_daemonID` (`daemonID`),
  KEY `status` (`status`),
  KEY `key_modified` (`dateModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_daemon`;

CREATE TABLE `daemon_logevent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logID` int(10) unsigned NOT NULL,
  `logType` varchar(4) NOT NULL,
  `message` longtext NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `logID` (`logID`,`epoch`),
  KEY `key_epoch` (`epoch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_dashboard` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `layoutConfig` longtext NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `status` varchar(32) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `icon` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_dashboard_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_dashboard_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_dashboard_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_dashboard_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_panel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `panelType` varchar(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `isArchived` tinyint(1) NOT NULL DEFAULT 0,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_panel_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_panel_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_panel_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_panel_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_paneltransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_portal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(32) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_portal_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_portal_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_portal_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_portal_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_portaltransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `dashboard_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_dashboard`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_differential` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_affectedpath` (
  `repositoryID` int(10) unsigned DEFAULT NULL,
  `pathID` int(10) unsigned NOT NULL,
  `revisionID` int(10) unsigned NOT NULL,
  KEY `revisionID` (`revisionID`),
  KEY `key_path` (`pathID`,`repositoryID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_changeset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `diffID` int(10) unsigned NOT NULL,
  `oldFile` longblob DEFAULT NULL,
  `filename` longblob NOT NULL,
  `awayPaths` longtext DEFAULT NULL,
  `changeType` int(10) unsigned NOT NULL,
  `fileType` int(10) unsigned NOT NULL,
  `metadata` longtext DEFAULT NULL,
  `oldProperties` longtext DEFAULT NULL,
  `newProperties` longtext DEFAULT NULL,
  `addLines` int(10) unsigned NOT NULL,
  `delLines` int(10) unsigned NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `phid` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `diffID` (`diffID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_changeset_parse_cache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cacheIndex` binary(12) NOT NULL,
  `cache` longblob NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_cacheIndex` (`cacheIndex`),
  KEY `key_created` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_customfieldnumericindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `indexKey` binary(12) NOT NULL,
  `indexValue` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_join` (`objectPHID`,`indexKey`,`indexValue`),
  KEY `key_find` (`indexKey`,`indexValue`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_customfieldstorage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `fieldIndex` binary(12) NOT NULL,
  `fieldValue` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `objectPHID` (`objectPHID`,`fieldIndex`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_customfieldstringindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `indexKey` binary(12) NOT NULL,
  `indexValue` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_join` (`objectPHID`,`indexKey`,`indexValue`(64)),
  KEY `key_find` (`indexKey`,`indexValue`(64))
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_diff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `revisionID` int(10) unsigned DEFAULT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `repositoryPHID` varbinary(64) DEFAULT NULL,
  `sourceMachine` varchar(255) DEFAULT NULL,
  `sourcePath` varchar(255) DEFAULT NULL,
  `sourceControlSystem` varchar(64) DEFAULT NULL,
  `sourceControlBaseRevision` varchar(255) DEFAULT NULL,
  `sourceControlPath` varchar(255) DEFAULT NULL,
  `lintStatus` int(10) unsigned NOT NULL,
  `unitStatus` int(10) unsigned NOT NULL,
  `lineCount` int(10) unsigned NOT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `bookmark` varchar(255) DEFAULT NULL,
  `creationMethod` varchar(255) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `repositoryUUID` varchar(64) DEFAULT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `commitPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `revisionID` (`revisionID`),
  KEY `key_commit` (`commitPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_diffproperty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `diffID` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `data` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `diffID` (`diffID`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_difftransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_hiddencomment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userPHID` varbinary(64) NOT NULL,
  `commentID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_user` (`userPHID`,`commentID`),
  KEY `key_comment` (`commentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_hunk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `changesetID` int(10) unsigned NOT NULL,
  `oldOffset` int(10) unsigned NOT NULL,
  `oldLen` int(10) unsigned NOT NULL,
  `newOffset` int(10) unsigned NOT NULL,
  `newLen` int(10) unsigned NOT NULL,
  `dataType` binary(4) NOT NULL,
  `dataEncoding` varchar(16) DEFAULT NULL,
  `dataFormat` binary(4) NOT NULL,
  `data` longblob NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_changeset` (`changesetID`),
  KEY `key_created` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_reviewer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `revisionPHID` varbinary(64) NOT NULL,
  `reviewerPHID` varbinary(64) NOT NULL,
  `reviewerStatus` varchar(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `lastActionDiffPHID` varbinary(64) DEFAULT NULL,
  `lastCommentDiffPHID` varbinary(64) DEFAULT NULL,
  `lastActorPHID` varbinary(64) DEFAULT NULL,
  `voidedPHID` varbinary(64) DEFAULT NULL,
  `options` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_revision` (`revisionPHID`,`reviewerPHID`),
  KEY `key_reviewer` (`reviewerPHID`,`revisionPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_revision` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `phid` varbinary(64) NOT NULL,
  `status` varchar(32) NOT NULL,
  `summary` longtext NOT NULL,
  `testPlan` longtext NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `lastReviewerPHID` varbinary(64) DEFAULT NULL,
  `lineCount` int(10) unsigned DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `attached` longtext NOT NULL,
  `mailKey` binary(40) NOT NULL,
  `branchName` varchar(255) DEFAULT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `repositoryPHID` varbinary(64) DEFAULT NULL,
  `properties` longtext NOT NULL,
  `activeDiffPHID` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `authorPHID` (`authorPHID`,`status`),
  KEY `repositoryPHID` (`repositoryPHID`),
  KEY `key_status` (`status`,`phid`),
  KEY `key_modified` (`dateModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_revision_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_revision_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_revision_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_revision_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_revisionhash` (
  `revisionID` int(10) unsigned NOT NULL,
  `type` binary(4) NOT NULL,
  `hash` binary(40) NOT NULL,
  KEY `type` (`type`,`hash`),
  KEY `revisionID` (`revisionID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `revisionPHID` varbinary(64) DEFAULT NULL,
  `changesetID` int(10) unsigned DEFAULT NULL,
  `isNewFile` tinyint(1) NOT NULL,
  `lineNumber` int(10) unsigned NOT NULL,
  `lineLength` int(10) unsigned NOT NULL,
  `fixedState` varchar(12) DEFAULT NULL,
  `hasReplies` tinyint(1) NOT NULL,
  `replyToCommentPHID` varbinary(64) DEFAULT NULL,
  `attributes` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`),
  KEY `key_changeset` (`changesetID`),
  KEY `key_draft` (`authorPHID`,`transactionPHID`),
  KEY `key_revision` (`revisionPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `differential_viewstate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `viewerPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewState` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_viewer` (`viewerPHID`,`objectPHID`),
  KEY `key_object` (`objectPHID`),
  KEY `key_modified` (`dateModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_differential`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_diviner` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_diviner`;

CREATE TABLE `diviner_liveatom` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `symbolPHID` varbinary(64) NOT NULL,
  `content` longtext NOT NULL,
  `atomData` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `symbolPHID` (`symbolPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_diviner`;

CREATE TABLE `diviner_livebook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `repositoryPHID` varbinary(64) DEFAULT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `configurationData` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_diviner`;

CREATE TABLE `diviner_livebooktransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_diviner`;

CREATE TABLE `diviner_livesymbol` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `bookPHID` varbinary(64) NOT NULL,
  `repositoryPHID` varbinary(64) DEFAULT NULL,
  `context` varchar(255) DEFAULT NULL,
  `type` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `atomIndex` int(10) unsigned NOT NULL,
  `identityHash` binary(12) NOT NULL,
  `graphHash` varchar(64) DEFAULT NULL,
  `title` longtext DEFAULT NULL,
  `order` int(10) unsigned NOT NULL,
  `titleSlugHash` binary(12) DEFAULT NULL,
  `groupName` varchar(255) DEFAULT NULL,
  `summary` longtext DEFAULT NULL,
  `isDocumentable` tinyint(1) NOT NULL,
  `nodeHash` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identityHash` (`identityHash`),
  UNIQUE KEY `phid` (`phid`),
  UNIQUE KEY `graphHash` (`graphHash`),
  UNIQUE KEY `nodeHash` (`nodeHash`),
  KEY `key_slug` (`titleSlugHash`),
  KEY `bookPHID` (`bookPHID`,`type`,`name`(64),`context`(64),`atomIndex`),
  KEY `name` (`name`(64))
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_diviner`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_diviner`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_doorkeeper` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_doorkeeper`;

CREATE TABLE `doorkeeper_externalobject` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `objectKey` binary(12) NOT NULL,
  `applicationType` varchar(32) NOT NULL,
  `applicationDomain` varchar(32) NOT NULL,
  `objectType` varchar(32) NOT NULL,
  `objectID` varchar(64) NOT NULL,
  `objectURI` varchar(128) DEFAULT NULL,
  `importerPHID` varbinary(64) DEFAULT NULL,
  `properties` longtext NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_object` (`objectKey`),
  KEY `key_full` (`applicationType`,`applicationDomain`,`objectType`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_doorkeeper`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_doorkeeper`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_draft` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_draft`;

CREATE TABLE `draft` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `authorPHID` varbinary(64) NOT NULL,
  `draftKey` varchar(64) NOT NULL,
  `draft` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `authorPHID` (`authorPHID`,`draftKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_draft`;

CREATE TABLE `draft_versioneddraft` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`,`authorPHID`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_drydock` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `drydock_authorization` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `blueprintPHID` varbinary(64) NOT NULL,
  `blueprintAuthorizationState` varchar(32) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `objectAuthorizationState` varchar(32) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_unique` (`objectPHID`,`blueprintPHID`),
  KEY `key_blueprint` (`blueprintPHID`,`blueprintAuthorizationState`),
  KEY `key_object` (`objectPHID`,`objectAuthorizationState`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `drydock_blueprint` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `className` varchar(255) NOT NULL,
  `blueprintName` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `details` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isDisabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `drydock_blueprintname_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `drydock_blueprinttransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `drydock_command` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `authorPHID` varbinary(64) NOT NULL,
  `targetPHID` varbinary(64) NOT NULL,
  `command` varchar(32) NOT NULL,
  `isConsumed` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `properties` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_target` (`targetPHID`,`isConsumed`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `drydock_lease` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `status` varchar(32) NOT NULL,
  `until` int(10) unsigned DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `attributes` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `resourceType` varchar(128) NOT NULL,
  `resourcePHID` varbinary(64) DEFAULT NULL,
  `authorizingPHID` varbinary(64) NOT NULL,
  `acquiredEpoch` int(10) unsigned DEFAULT NULL,
  `activatedEpoch` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_resource` (`resourcePHID`,`status`),
  KEY `key_status` (`status`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_recent` (`resourcePHID`,`dateModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `drydock_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `epoch` int(10) unsigned NOT NULL,
  `blueprintPHID` varbinary(64) DEFAULT NULL,
  `resourcePHID` varbinary(64) DEFAULT NULL,
  `leasePHID` varbinary(64) DEFAULT NULL,
  `type` varchar(64) NOT NULL,
  `data` longtext NOT NULL,
  `operationPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `epoch` (`epoch`),
  KEY `key_blueprint` (`blueprintPHID`,`type`),
  KEY `key_resource` (`resourcePHID`,`type`),
  KEY `key_lease` (`leasePHID`,`type`),
  KEY `key_operation` (`operationPHID`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `drydock_repositoryoperation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `repositoryPHID` varbinary(64) NOT NULL,
  `repositoryTarget` longblob NOT NULL,
  `operationType` varchar(32) NOT NULL,
  `operationState` varchar(32) NOT NULL,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isDismissed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`),
  KEY `key_repository` (`repositoryPHID`,`operationState`),
  KEY `key_state` (`operationState`),
  KEY `key_author` (`authorPHID`,`operationState`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `drydock_resource` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `status` varchar(32) NOT NULL,
  `type` varchar(64) NOT NULL,
  `attributes` longtext NOT NULL,
  `capabilities` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `blueprintPHID` varbinary(64) NOT NULL,
  `until` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_type` (`type`,`status`),
  KEY `key_blueprint` (`blueprintPHID`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `drydock_slotlock` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ownerPHID` varbinary(64) NOT NULL,
  `lockIndex` binary(12) NOT NULL,
  `lockKey` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_lock` (`lockIndex`),
  KEY `key_owner` (`ownerPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_drydock`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_fact` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_fact`;

CREATE TABLE `fact_aggregate` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `factType` varchar(32) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `valueX` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `factType` (`factType`,`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_fact`;

CREATE TABLE `fact_chart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chartKey` binary(12) NOT NULL,
  `chartParameters` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_chart` (`chartKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_fact`;

CREATE TABLE `fact_cursor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `position` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_fact`;

CREATE TABLE `fact_intdatapoint` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `keyID` int(10) unsigned NOT NULL,
  `objectID` int(10) unsigned NOT NULL,
  `dimensionID` int(10) unsigned DEFAULT NULL,
  `value` bigint(20) NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_dimension` (`keyID`,`dimensionID`),
  KEY `key_object` (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_fact`;

CREATE TABLE `fact_keydimension` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `factKey` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_factkey` (`factKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_fact`;

CREATE TABLE `fact_objectdimension` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_fact`;

CREATE TABLE `fact_raw` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `factType` varchar(32) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `objectA` varbinary(64) NOT NULL,
  `valueX` bigint(20) NOT NULL,
  `valueY` bigint(20) NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `objectPHID` (`objectPHID`),
  KEY `factType` (`factType`,`epoch`),
  KEY `factType_2` (`factType`,`objectA`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_feed` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_feed`;

CREATE TABLE `feed_storydata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `chronologicalKey` bigint(20) unsigned NOT NULL,
  `storyType` varchar(64) NOT NULL,
  `storyData` longtext NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  UNIQUE KEY `chronologicalKey` (`chronologicalKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_feed`;

CREATE TABLE `feed_storynotification` (
  `userPHID` varbinary(64) NOT NULL,
  `primaryObjectPHID` varbinary(64) NOT NULL,
  `chronologicalKey` bigint(20) unsigned NOT NULL,
  `hasViewed` tinyint(1) NOT NULL,
  UNIQUE KEY `userPHID` (`userPHID`,`chronologicalKey`),
  KEY `userPHID_2` (`userPHID`,`hasViewed`,`primaryObjectPHID`),
  KEY `key_object` (`primaryObjectPHID`),
  KEY `key_chronological` (`chronologicalKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_feed`;

CREATE TABLE `feed_storyreference` (
  `objectPHID` varbinary(64) NOT NULL,
  `chronologicalKey` bigint(20) unsigned NOT NULL,
  UNIQUE KEY `objectPHID` (`objectPHID`,`chronologicalKey`),
  KEY `chronologicalKey` (`chronologicalKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_file` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_file`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} DEFAULT NULL,
  `mimeType` varchar(255) DEFAULT NULL,
  `byteSize` bigint(20) unsigned NOT NULL,
  `storageEngine` varchar(32) NOT NULL,
  `storageFormat` varchar(32) NOT NULL,
  `storageHandle` varchar(255) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `secretKey` binary(20) DEFAULT NULL,
  `contentHash` binary(64) DEFAULT NULL,
  `metadata` longtext NOT NULL,
  `ttl` int(10) unsigned DEFAULT NULL,
  `isExplicitUpload` tinyint(1) DEFAULT 1,
  `mailKey` binary(20) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `isPartial` tinyint(1) NOT NULL DEFAULT 0,
  `builtinKey` varchar(64) DEFAULT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  UNIQUE KEY `key_builtin` (`builtinKey`),
  KEY `authorPHID` (`authorPHID`),
  KEY `contentHash` (`contentHash`),
  KEY `key_ttl` (`ttl`),
  KEY `key_dateCreated` (`dateCreated`),
  KEY `key_partial` (`authorPHID`,`isPartial`),
  KEY `key_engine` (`storageEngine`,`storageHandle`(64))
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `file_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `filePHID` varbinary(64) NOT NULL,
  `attacherPHID` varbinary(64) DEFAULT NULL,
  `attachmentMode` varchar(32) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`,`filePHID`),
  KEY `key_file` (`filePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `file_chunk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chunkHandle` binary(12) NOT NULL,
  `byteStart` bigint(20) unsigned NOT NULL,
  `byteEnd` bigint(20) unsigned NOT NULL,
  `dataFilePHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key_file` (`chunkHandle`,`byteStart`,`byteEnd`),
  KEY `key_data` (`dataFilePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `file_externalrequest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filePHID` varbinary(64) DEFAULT NULL,
  `ttl` int(10) unsigned NOT NULL,
  `uri` longtext NOT NULL,
  `uriIndex` binary(12) NOT NULL,
  `isSuccessful` tinyint(1) NOT NULL,
  `responseMessage` longtext DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_uriindex` (`uriIndex`),
  KEY `key_ttl` (`ttl`),
  KEY `key_file` (`filePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `file_filename_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `file_imagemacro` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `filePHID` varbinary(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isDisabled` tinyint(1) NOT NULL,
  `audioPHID` varbinary(64) DEFAULT NULL,
  `audioBehavior` varchar(64) NOT NULL,
  `mailKey` binary(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `name` (`name`),
  KEY `key_disabled` (`isDisabled`),
  KEY `key_dateCreated` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `file_storageblob` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longblob NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `file_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `file_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `file_transformedfile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `originalPHID` varbinary(64) NOT NULL,
  `transform` varchar(128) NOT NULL,
  `transformedPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `originalPHID` (`originalPHID`,`transform`),
  KEY `transformedPHID` (`transformedPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `macro_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `metadata` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_file`;

CREATE TABLE `macro_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_flag` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_flag`;

CREATE TABLE `flag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ownerPHID` varbinary(64) NOT NULL,
  `type` varchar(4) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `reasonPHID` varbinary(64) NOT NULL,
  `color` int(10) unsigned NOT NULL,
  `note` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ownerPHID` (`ownerPHID`,`type`,`objectPHID`),
  KEY `objectPHID` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_harbormaster` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_build` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `buildablePHID` varbinary(64) NOT NULL,
  `buildPlanPHID` varbinary(64) NOT NULL,
  `buildStatus` varchar(32) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `buildGeneration` int(10) unsigned NOT NULL DEFAULT 0,
  `planAutoKey` varchar(32) DEFAULT NULL,
  `buildParameters` longtext NOT NULL,
  `initiatorPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_planautokey` (`buildablePHID`,`planAutoKey`),
  KEY `key_buildable` (`buildablePHID`),
  KEY `key_plan` (`buildPlanPHID`),
  KEY `key_status` (`buildStatus`),
  KEY `key_initiator` (`initiatorPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildable` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `buildablePHID` varbinary(64) NOT NULL,
  `containerPHID` varbinary(64) DEFAULT NULL,
  `buildableStatus` varchar(32) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isManualBuildable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_buildable` (`buildablePHID`),
  KEY `key_container` (`containerPHID`),
  KEY `key_manual` (`isManualBuildable`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildabletransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildartifact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `artifactType` varchar(32) NOT NULL,
  `artifactIndex` binary(12) NOT NULL,
  `artifactKey` varchar(255) NOT NULL,
  `artifactData` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `buildTargetPHID` varbinary(64) NOT NULL,
  `isReleased` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_artifact` (`artifactType`,`artifactIndex`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_garbagecollect` (`artifactType`,`dateCreated`),
  KEY `key_target` (`buildTargetPHID`,`artifactType`),
  KEY `key_index` (`artifactIndex`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildlintmessage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `buildTargetPHID` varbinary(64) NOT NULL,
  `path` longtext NOT NULL,
  `line` int(10) unsigned DEFAULT NULL,
  `characterOffset` int(10) unsigned DEFAULT NULL,
  `code` varchar(128) NOT NULL,
  `severity` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_target` (`buildTargetPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `logSource` varchar(255) DEFAULT NULL,
  `logType` varchar(255) DEFAULT NULL,
  `duration` int(10) unsigned DEFAULT NULL,
  `live` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `buildTargetPHID` varbinary(64) NOT NULL,
  `filePHID` varbinary(64) DEFAULT NULL,
  `byteLength` bigint(20) unsigned NOT NULL,
  `chunkFormat` varchar(32) NOT NULL,
  `lineMap` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_buildtarget` (`buildTargetPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildlogchunk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `logID` int(10) unsigned NOT NULL,
  `encoding` varchar(32) NOT NULL,
  `size` int(10) unsigned DEFAULT NULL,
  `chunk` longblob NOT NULL,
  `headOffset` bigint(20) unsigned NOT NULL,
  `tailOffset` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_offset` (`logID`,`headOffset`,`tailOffset`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildmessage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `authorPHID` varbinary(64) NOT NULL,
  `receiverPHID` varbinary(64) NOT NULL,
  `type` varchar(16) NOT NULL,
  `isConsumed` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_receiver` (`receiverPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildplan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(128) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `planStatus` varchar(32) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `planAutoKey` varchar(32) DEFAULT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `properties` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_planautokey` (`planAutoKey`),
  KEY `key_status` (`planStatus`),
  KEY `key_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildplanname_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildplantransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildstep` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `buildPlanPHID` varbinary(64) NOT NULL,
  `className` varchar(255) NOT NULL,
  `details` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `sequence` int(10) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext NOT NULL,
  `stepAutoKey` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_stepautokey` (`buildPlanPHID`,`stepAutoKey`),
  KEY `key_plan` (`buildPlanPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildsteptransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildtarget` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `buildPHID` varbinary(64) NOT NULL,
  `buildStepPHID` varbinary(64) NOT NULL,
  `className` varchar(255) NOT NULL,
  `details` longtext NOT NULL,
  `variables` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `targetStatus` varchar(64) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dateStarted` int(10) unsigned DEFAULT NULL,
  `dateCompleted` int(10) unsigned DEFAULT NULL,
  `buildGeneration` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_build` (`buildPHID`,`buildStepPHID`),
  KEY `key_started` (`dateStarted`),
  KEY `key_completed` (`dateCompleted`),
  KEY `key_created` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_buildunitmessage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `buildTargetPHID` varbinary(64) NOT NULL,
  `engine` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `result` varchar(32) NOT NULL,
  `duration` double DEFAULT NULL,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `nameIndex` binary(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_target` (`buildTargetPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_object` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_scratchtable` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` varchar(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `bigData` longtext DEFAULT NULL,
  `nonmutableData` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `data` (`data`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `harbormaster_string` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stringIndex` binary(12) NOT NULL,
  `stringValue` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_string` (`stringIndex`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_harbormaster`;

CREATE TABLE `lisk_counter` (
  `counterName` varchar(32) NOT NULL,
  `counterValue` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`counterName`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_herald` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_herald`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `herald_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ruleID` int(10) unsigned NOT NULL,
  `action` varchar(255) NOT NULL,
  `target` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ruleID` (`ruleID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `herald_condition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ruleID` int(10) unsigned NOT NULL,
  `fieldName` varchar(255) NOT NULL,
  `fieldCondition` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ruleID` (`ruleID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `herald_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `contentType` varchar(255) NOT NULL,
  `mustMatchAll` tinyint(1) NOT NULL,
  `configVersion` int(10) unsigned NOT NULL DEFAULT 1,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `repetitionPolicy` varchar(32) NOT NULL,
  `ruleType` varchar(32) NOT NULL,
  `phid` varbinary(64) NOT NULL,
  `isDisabled` int(10) unsigned NOT NULL DEFAULT 0,
  `triggerObjectPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_trigger` (`triggerObjectPHID`),
  KEY `key_name` (`name`(128)),
  KEY `key_author` (`authorPHID`),
  KEY `key_ruletype` (`ruleType`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `herald_ruleapplied` (
  `ruleID` int(10) unsigned NOT NULL,
  `phid` varbinary(64) NOT NULL,
  PRIMARY KEY (`ruleID`,`phid`),
  KEY `phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `herald_ruletransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `herald_savedheader` (
  `phid` varbinary(64) NOT NULL,
  `header` longtext NOT NULL,
  PRIMARY KEY (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `herald_transcript` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `host` varchar(255) NOT NULL,
  `duration` double NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `dryRun` tinyint(1) NOT NULL,
  `objectTranscript` longblob NOT NULL,
  `ruleTranscripts` longblob NOT NULL,
  `conditionTranscripts` longblob NOT NULL,
  `applyTranscripts` longblob NOT NULL,
  `garbageCollected` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  KEY `objectPHID` (`objectPHID`),
  KEY `garbageCollected` (`garbageCollected`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `herald_webhook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `webhookURI` longtext NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `status` varchar(32) NOT NULL,
  `hmacKey` varchar(32) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `herald_webhookrequest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `webhookPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `status` varchar(32) NOT NULL,
  `properties` longtext NOT NULL,
  `lastRequestResult` varchar(32) NOT NULL,
  `lastRequestEpoch` int(10) unsigned NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_ratelimit` (`webhookPHID`,`lastRequestResult`,`lastRequestEpoch`),
  KEY `key_collect` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_herald`;

CREATE TABLE `herald_webhooktransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_legalpad` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_legalpad`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_legalpad`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_legalpad`;

CREATE TABLE `legalpad_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `contributorCount` int(10) unsigned NOT NULL DEFAULT 0,
  `recentContributorPHIDs` longtext NOT NULL,
  `creatorPHID` varbinary(64) NOT NULL,
  `versions` int(10) unsigned NOT NULL DEFAULT 0,
  `documentBodyPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `mailKey` binary(20) NOT NULL,
  `signatureType` varchar(4) NOT NULL,
  `preamble` longtext NOT NULL,
  `requireSignature` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_creator` (`creatorPHID`,`dateModified`),
  KEY `key_required` (`requireSignature`,`dateModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_legalpad`;

CREATE TABLE `legalpad_documentbody` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `creatorPHID` varbinary(64) NOT NULL,
  `documentPHID` varbinary(64) NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `text` longtext DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_document` (`documentPHID`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_legalpad`;

CREATE TABLE `legalpad_documentsignature` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentPHID` varbinary(64) NOT NULL,
  `documentVersion` int(10) unsigned NOT NULL DEFAULT 0,
  `signatureType` varchar(4) NOT NULL,
  `signerPHID` varbinary(64) DEFAULT NULL,
  `signerName` varchar(255) NOT NULL,
  `signerEmail` varchar(255) NOT NULL,
  `signatureData` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `secretKey` binary(20) NOT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `isExemption` tinyint(1) NOT NULL DEFAULT 0,
  `exemptionPHID` varbinary(64) DEFAULT NULL,
  `phid` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_signer` (`signerPHID`,`dateModified`),
  KEY `secretKey` (`secretKey`),
  KEY `key_document` (`documentPHID`,`signerPHID`,`documentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_legalpad`;

CREATE TABLE `legalpad_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_legalpad`;

CREATE TABLE `legalpad_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `documentID` int(10) unsigned DEFAULT NULL,
  `lineNumber` int(10) unsigned NOT NULL,
  `lineLength` int(10) unsigned NOT NULL,
  `fixedState` varchar(12) DEFAULT NULL,
  `hasReplies` tinyint(1) NOT NULL,
  `replyToCommentPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`),
  UNIQUE KEY `key_draft` (`authorPHID`,`documentID`,`transactionPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_maniphest` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_customfieldnumericindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `indexKey` binary(12) NOT NULL,
  `indexValue` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_join` (`objectPHID`,`indexKey`,`indexValue`),
  KEY `key_find` (`indexKey`,`indexValue`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_customfieldstorage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `fieldIndex` binary(12) NOT NULL,
  `fieldValue` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `objectPHID` (`objectPHID`,`fieldIndex`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_customfieldstringindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `indexKey` binary(12) NOT NULL,
  `indexValue` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_join` (`objectPHID`,`indexKey`,`indexValue`(64)),
  KEY `key_find` (`indexKey`,`indexValue`(64))
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_nameindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indexedObjectPHID` varbinary(64) NOT NULL,
  `indexedObjectName` varchar(128) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`indexedObjectPHID`),
  KEY `key_name` (`indexedObjectName`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `status` varchar(64) NOT NULL,
  `priority` int(10) unsigned NOT NULL,
  `title` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `description` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `mailKey` binary(20) NOT NULL,
  `ownerOrdering` varchar(64) DEFAULT NULL,
  `originalEmailSource` varchar(255) DEFAULT NULL,
  `subpriority` double NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  `properties` longtext NOT NULL,
  `points` double DEFAULT NULL,
  `bridgedObjectPHID` varbinary(64) DEFAULT NULL,
  `subtype` varchar(64) NOT NULL,
  `closedEpoch` int(10) unsigned DEFAULT NULL,
  `closerPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  UNIQUE KEY `key_bridgedobject` (`bridgedObjectPHID`),
  KEY `priority` (`priority`,`status`),
  KEY `status` (`status`),
  KEY `ownerPHID` (`ownerPHID`,`status`),
  KEY `authorPHID` (`authorPHID`,`status`),
  KEY `ownerOrdering` (`ownerOrdering`),
  KEY `priority_2` (`priority`,`subpriority`),
  KEY `key_dateCreated` (`dateCreated`),
  KEY `key_dateModified` (`dateModified`),
  KEY `key_title` (`title`(64)),
  KEY `key_subtype` (`subtype`),
  KEY `key_closed` (`closedEpoch`),
  KEY `key_closer` (`closerPHID`,`closedEpoch`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_task_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_task_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_task_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_task_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_maniphest`;

CREATE TABLE `maniphest_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_meta_data` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_meta_data`;

CREATE TABLE `hoststate` (
  `stateKey` varchar(128) NOT NULL,
  `stateValue` longtext NOT NULL,
  PRIMARY KEY (`stateKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_meta_data`;

CREATE TABLE `patch_status` (
  `patch` varchar(128) NOT NULL,
  `applied` int(10) unsigned NOT NULL,
  `duration` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`patch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

INSERT INTO `patch_status` VALUES
('phabricator:00.application.0.db',1786875800,NULL),
('phabricator:00.system.0.db',1786875800,NULL),
('phabricator:000.project.sql',1786875777,NULL),
('phabricator:0000.legacy.sql',1786875777,NULL),
('phabricator:001.maniphest_projects.sql',1786875777,NULL),
('phabricator:002.oauth.sql',1786875777,NULL),
('phabricator:003.more_oauth.sql',1786875777,NULL),
('phabricator:004.daemonrepos.sql',1786875777,NULL),
('phabricator:005.workers.sql',1786875777,NULL),
('phabricator:006.repository.sql',1786875778,NULL),
('phabricator:007.daemonlog.sql',1786875778,NULL),
('phabricator:008.repoopt.sql',1786875778,NULL),
('phabricator:009.repo_summary.sql',1786875778,NULL),
('phabricator:010.herald.sql',1786875778,NULL),
('phabricator:011.badcommit.sql',1786875778,NULL),
('phabricator:012.dropphidtype.sql',1786875778,NULL),
('phabricator:013.commitdetail.sql',1786875778,NULL),
('phabricator:014.shortcuts.sql',1786875778,NULL),
('phabricator:015.preferences.sql',1786875778,NULL),
('phabricator:016.userrealnameindex.sql',1786875778,NULL),
('phabricator:017.sessionkeys.sql',1786875778,NULL),
('phabricator:018.owners.sql',1786875778,NULL),
('phabricator:019.arcprojects.sql',1786875778,NULL),
('phabricator:020.pathcapital.sql',1786875778,NULL),
('phabricator:021.xhpastview.sql',1786875778,NULL),
('phabricator:022.differentialcommit.sql',1786875779,NULL),
('phabricator:023.dxkeys.sql',1786875779,NULL),
('phabricator:024.mlistkeys.sql',1786875779,NULL),
('phabricator:025.commentopt.sql',1786875779,NULL),
('phabricator:026.diffpropkey.sql',1786875779,NULL),
('phabricator:027.metamtakeys.sql',1786875779,NULL),
('phabricator:028.systemagent.sql',1786875779,NULL),
('phabricator:029.cursors.sql',1786875779,NULL),
('phabricator:030.imagemacro.sql',1786875779,NULL),
('phabricator:031.workerrace.sql',1786875779,NULL),
('phabricator:032.viewtime.sql',1786875779,NULL),
('phabricator:033.privtest.sql',1786875779,NULL),
('phabricator:034.savedheader.sql',1786875779,NULL),
('phabricator:035.proxyimage.sql',1786875779,NULL),
('phabricator:036.mailkey.sql',1786875779,NULL),
('phabricator:037.setuptest.sql',1786875779,NULL),
('phabricator:038.admin.sql',1786875780,NULL),
('phabricator:039.userlog.sql',1786875780,NULL),
('phabricator:040.transform.sql',1786875780,NULL),
('phabricator:041.heraldrepetition.sql',1786875780,NULL),
('phabricator:042.commentmetadata.sql',1786875780,NULL),
('phabricator:043.pastebin.sql',1786875780,NULL),
('phabricator:044.countdown.sql',1786875780,NULL),
('phabricator:045.timezone.sql',1786875780,NULL),
('phabricator:046.conduittoken.sql',1786875780,NULL),
('phabricator:047.projectstatus.sql',1786875780,NULL),
('phabricator:048.relationshipkeys.sql',1786875780,NULL),
('phabricator:049.projectowner.sql',1786875780,NULL),
('phabricator:050.taskdenormal.sql',1786875780,NULL),
('phabricator:051.projectfilter.sql',1786875780,NULL),
('phabricator:052.pastelanguage.sql',1786875780,NULL),
('phabricator:053.feed.sql',1786875780,NULL),
('phabricator:054.subscribers.sql',1786875780,NULL),
('phabricator:055.add_author_to_files.sql',1786875780,NULL),
('phabricator:056.slowvote.sql',1786875781,NULL),
('phabricator:057.parsecache.sql',1786875781,NULL),
('phabricator:058.missingkeys.sql',1786875781,NULL),
('phabricator:059.engines.php',1786875781,NULL),
('phabricator:060.phriction.sql',1786875781,NULL),
('phabricator:061.phrictioncontent.sql',1786875781,NULL),
('phabricator:062.phrictionmenu.sql',1786875781,NULL),
('phabricator:063.pasteforks.sql',1786875781,NULL),
('phabricator:064.subprojects.sql',1786875781,NULL),
('phabricator:065.sshkeys.sql',1786875781,NULL),
('phabricator:066.phrictioncontent.sql',1786875781,NULL),
('phabricator:067.preferences.sql',1786875781,NULL),
('phabricator:068.maniphestauxiliarystorage.sql',1786875781,NULL),
('phabricator:069.heraldxscript.sql',1786875781,NULL),
('phabricator:070.differentialaux.sql',1786875781,NULL),
('phabricator:071.contentsource.sql',1786875781,NULL),
('phabricator:072.blamerevert.sql',1786875781,NULL),
('phabricator:073.reposymbols.sql',1786875781,NULL),
('phabricator:074.affectedpath.sql',1786875781,NULL),
('phabricator:075.revisionhash.sql',1786875781,NULL),
('phabricator:076.indexedlanguages.sql',1786875781,NULL),
('phabricator:077.originalemail.sql',1786875781,NULL),
('phabricator:078.nametoken.sql',1786875781,NULL),
('phabricator:079.nametokenindex.php',1786875781,NULL),
('phabricator:080.filekeys.sql',1786875781,NULL),
('phabricator:081.filekeys.php',1786875781,NULL),
('phabricator:082.xactionkey.sql',1786875781,NULL),
('phabricator:083.dxviewtime.sql',1786875782,NULL),
('phabricator:084.pasteauthorkey.sql',1786875782,NULL),
('phabricator:085.packagecommitrelationship.sql',1786875782,NULL),
('phabricator:086.formeraffil.sql',1786875782,NULL),
('phabricator:087.phrictiondelete.sql',1786875782,NULL),
('phabricator:088.audit.sql',1786875782,NULL),
('phabricator:089.projectwiki.sql',1786875782,NULL),
('phabricator:090.forceuniqueprojectnames.php',1786875782,NULL),
('phabricator:091.uniqueslugkey.sql',1786875782,NULL),
('phabricator:092.dropgithubnotification.sql',1786875782,NULL),
('phabricator:093.gitremotes.php',1786875782,NULL),
('phabricator:094.phrictioncolumn.sql',1786875782,NULL),
('phabricator:095.directory.sql',1786875782,NULL),
('phabricator:096.filename.sql',1786875782,NULL),
('phabricator:097.heraldruletypes.sql',1786875782,NULL),
('phabricator:098.heraldruletypemigration.php',1786875782,NULL),
('phabricator:099.drydock.sql',1786875782,NULL),
('phabricator:100.projectxaction.sql',1786875782,NULL),
('phabricator:101.heraldruleapplied.sql',1786875782,NULL),
('phabricator:102.heraldcleanup.php',1786875782,NULL),
('phabricator:103.heraldedithistory.sql',1786875782,NULL),
('phabricator:104.searchkey.sql',1786875782,NULL),
('phabricator:105.mimetype.sql',1786875782,NULL),
('phabricator:106.chatlog.sql',1786875782,NULL),
('phabricator:107.oauthserver.sql',1786875782,NULL),
('phabricator:108.oauthscope.sql',1786875782,NULL),
('phabricator:109.oauthclientphidkey.sql',1786875782,NULL),
('phabricator:110.commitaudit.sql',1786875782,NULL),
('phabricator:111.commitauditmigration.php',1786875782,NULL),
('phabricator:112.oauthaccesscoderedirecturi.sql',1786875782,NULL),
('phabricator:113.lastreviewer.sql',1786875783,NULL),
('phabricator:114.auditrequest.sql',1786875783,NULL),
('phabricator:115.prepareutf8.sql',1786875783,NULL),
('phabricator:116.utf8-backup-first-expect-wait.sql',1786875790,NULL),
('phabricator:117.repositorydescription.php',1786875790,NULL),
('phabricator:118.auditinline.sql',1786875790,NULL),
('phabricator:119.filehash.sql',1786875790,NULL),
('phabricator:120.noop.sql',1786875790,NULL),
('phabricator:121.drydocklog.sql',1786875790,NULL),
('phabricator:122.flag.sql',1786875790,NULL),
('phabricator:123.heraldrulelog.sql',1786875790,NULL),
('phabricator:124.subpriority.sql',1786875790,NULL),
('phabricator:125.ipv6.sql',1786875791,NULL),
('phabricator:126.edges.sql',1786875791,NULL),
('phabricator:127.userkeybody.sql',1786875791,NULL),
('phabricator:128.phabricatorcom.sql',1786875791,NULL),
('phabricator:129.savedquery.sql',1786875791,NULL),
('phabricator:130.denormalrevisionquery.sql',1786875791,NULL),
('phabricator:131.migraterevisionquery.php',1786875791,NULL),
('phabricator:132.phame.sql',1786875791,NULL),
('phabricator:133.imagemacro.sql',1786875791,NULL),
('phabricator:134.emptysearch.sql',1786875791,NULL),
('phabricator:135.datecommitted.sql',1786875791,NULL),
('phabricator:136.sex.sql',1786875791,NULL),
('phabricator:137.auditmetadata.sql',1786875791,NULL),
('phabricator:138.notification.sql',1786875791,NULL),
('phabricator:20121209.pholioxactions.sql',1786875793,NULL),
('phabricator:20121209.xmacroadd.sql',1786875793,NULL),
('phabricator:20121209.xmacromigrate.php',1786875793,NULL),
('phabricator:20121209.xmacromigratekey.sql',1786875793,NULL),
('phabricator:20121220.generalcache.sql',1786875793,NULL),
('phabricator:20121226.config.sql',1786875793,NULL),
('phabricator:20130101.confxaction.sql',1786875793,NULL),
('phabricator:20130102.metamtareceivedmailmessageidhash.sql',1786875793,NULL),
('phabricator:20130103.filemetadata.sql',1786875793,NULL),
('phabricator:20130111.conpherence.sql',1786875794,NULL),
('phabricator:20130127.altheraldtranscript.sql',1786875794,NULL),
('phabricator:20130131.conpherencepics.sql',1786875794,NULL),
('phabricator:20130201.revisionunsubscribed.php',1786875794,NULL),
('phabricator:20130201.revisionunsubscribed.sql',1786875794,NULL),
('phabricator:20130214.chatlogchannel.sql',1786875794,NULL),
('phabricator:20130214.chatlogchannelid.sql',1786875794,NULL),
('phabricator:20130214.token.sql',1786875794,NULL),
('phabricator:20130215.phabricatorfileaddttl.sql',1786875794,NULL),
('phabricator:20130217.cachettl.sql',1786875794,NULL),
('phabricator:20130218.longdaemon.sql',1786875794,NULL),
('phabricator:20130218.updatechannelid.php',1786875794,NULL),
('phabricator:20130219.commitsummary.sql',1786875794,NULL),
('phabricator:20130219.commitsummarymig.php',1786875794,NULL),
('phabricator:20130222.dropchannel.sql',1786875794,NULL),
('phabricator:20130226.commitkey.sql',1786875794,NULL),
('phabricator:20130304.lintauthor.sql',1786875794,NULL),
('phabricator:20130310.xactionmeta.sql',1786875794,NULL),
('phabricator:20130317.phrictionedge.sql',1786875794,NULL),
('phabricator:20130319.conpherence.sql',1786875794,NULL),
('phabricator:20130319.phabricatorfileexplicitupload.sql',1786875794,NULL),
('phabricator:20130320.phlux.sql',1786875794,NULL),
('phabricator:20130321.token.sql',1786875794,NULL),
('phabricator:20130322.phortune.sql',1786875794,NULL),
('phabricator:20130323.phortunepayment.sql',1786875794,NULL),
('phabricator:20130324.phortuneproduct.sql',1786875794,NULL),
('phabricator:20130330.phrequent.sql',1786875794,NULL),
('phabricator:20130403.conpherencecache.sql',1786875794,NULL),
('phabricator:20130403.conpherencecachemig.php',1786875794,NULL),
('phabricator:20130409.commitdrev.php',1786875794,NULL),
('phabricator:20130417.externalaccount.sql',1786875794,NULL),
('phabricator:20130423.conpherenceindices.sql',1786875795,NULL),
('phabricator:20130423.phortunepaymentrevised.sql',1786875794,NULL),
('phabricator:20130423.updateexternalaccount.sql',1786875794,NULL),
('phabricator:20130426.search_savedquery.sql',1786875795,NULL),
('phabricator:20130502.countdownrevamp1.sql',1786875795,NULL),
('phabricator:20130502.countdownrevamp2.php',1786875795,NULL),
('phabricator:20130502.countdownrevamp3.sql',1786875795,NULL),
('phabricator:20130508.search_namedquery.sql',1786875795,NULL),
('phabricator:20130513.receviedmailstatus.sql',1786875795,NULL),
('phabricator:20130519.diviner.sql',1786875795,NULL),
('phabricator:20130521.dropconphimages.sql',1786875795,NULL),
('phabricator:20130523.maniphest_owners.sql',1786875795,NULL),
('phabricator:20130524.repoxactions.sql',1786875795,NULL),
('phabricator:20130529.macroauthor.sql',1786875795,NULL),
('phabricator:20130529.macroauthormig.php',1786875795,NULL),
('phabricator:20130530.macrodatekey.sql',1786875795,NULL),
('phabricator:20130530.pastekeys.sql',1786875795,NULL),
('phabricator:20130530.sessionhash.php',1786875795,NULL),
('phabricator:20130531.filekeys.sql',1786875795,NULL),
('phabricator:20130602.morediviner.sql',1786875795,NULL),
('phabricator:20130602.namedqueries.sql',1786875795,NULL),
('phabricator:20130606.userxactions.sql',1786875795,NULL),
('phabricator:20130607.xaccount.sql',1786875796,NULL),
('phabricator:20130611.migrateoauth.php',1786875796,NULL),
('phabricator:20130611.nukeldap.php',1786875796,NULL),
('phabricator:20130613.authdb.sql',1786875796,NULL),
('phabricator:20130619.authconf.php',1786875796,NULL),
('phabricator:20130620.diffxactions.sql',1786875796,NULL),
('phabricator:20130621.diffcommentphid.sql',1786875796,NULL),
('phabricator:20130621.diffcommentphidmig.php',1786875796,NULL),
('phabricator:20130621.diffcommentunphid.sql',1786875796,NULL),
('phabricator:20130622.doorkeeper.sql',1786875796,NULL),
('phabricator:20130628.legalpadv0.sql',1786875796,NULL),
('phabricator:20130701.conduitlog.sql',1786875796,NULL),
('phabricator:20130703.legalpaddocdenorm.php',1786875796,NULL),
('phabricator:20130703.legalpaddocdenorm.sql',1786875796,NULL),
('phabricator:20130709.droptimeline.sql',1786875796,NULL),
('phabricator:20130709.legalpadsignature.sql',1786875796,NULL),
('phabricator:20130711.pholioimageobsolete.php',1786875797,NULL),
('phabricator:20130711.pholioimageobsolete.sql',1786875797,NULL),
('phabricator:20130711.pholioimageobsolete2.sql',1786875797,NULL),
('phabricator:20130711.trimrealnames.php',1786875796,NULL),
('phabricator:20130714.votexactions.sql',1786875796,NULL),
('phabricator:20130715.votecomments.php',1786875796,NULL),
('phabricator:20130715.voteedges.sql',1786875796,NULL),
('phabricator:20130716.archivememberlessprojects.php',1786875797,NULL),
('phabricator:20130722.pholioreplace.sql',1786875797,NULL),
('phabricator:20130723.taskstarttime.sql',1786875797,NULL),
('phabricator:20130726.ponderxactions.sql',1786875797,NULL),
('phabricator:20130727.ponderquestionstatus.sql',1786875797,NULL),
('phabricator:20130728.ponderunique.php',1786875797,NULL),
('phabricator:20130728.ponderuniquekey.sql',1786875797,NULL),
('phabricator:20130728.ponderxcomment.php',1786875797,NULL),
('phabricator:20130801.pastexactions.php',1786875797,NULL),
('phabricator:20130801.pastexactions.sql',1786875797,NULL),
('phabricator:20130802.heraldphid.sql',1786875797,NULL),
('phabricator:20130802.heraldphids.php',1786875797,NULL),
('phabricator:20130802.heraldphidukey.sql',1786875797,NULL),
('phabricator:20130802.heraldxactions.sql',1786875797,NULL),
('phabricator:20130805.pasteedges.sql',1786875797,NULL),
('phabricator:20130805.pastemailkey.sql',1786875797,NULL),
('phabricator:20130814.usercustom.sql',1786875797,NULL),
('phabricator:20130820.file-mailkey-populate.php',1786875797,NULL),
('phabricator:20130820.filemailkey.sql',1786875797,NULL),
('phabricator:20130820.filexactions.sql',1786875797,NULL),
('phabricator:20130826.divinernode.sql',1786875797,NULL),
('phabricator:20130912.maniphest.1.touch.sql',1786875797,NULL),
('phabricator:20130912.maniphest.2.created.sql',1786875797,NULL),
('phabricator:20130912.maniphest.3.nameindex.sql',1786875797,NULL),
('phabricator:20130912.maniphest.4.fillindex.php',1786875797,NULL),
('phabricator:20130913.maniphest.1.migratesearch.php',1786875797,NULL),
('phabricator:20130914.usercustom.sql',1786875798,NULL),
('phabricator:20130915.maniphestcustom.sql',1786875798,NULL),
('phabricator:20130915.maniphestmigrate.php',1786875798,NULL),
('phabricator:20130915.maniphestqdrop.sql',1786875798,NULL),
('phabricator:20130919.mfieldconf.php',1786875798,NULL),
('phabricator:20130920.repokeyspolicy.sql',1786875798,NULL),
('phabricator:20130921.mtransactions.sql',1786875798,NULL),
('phabricator:20130921.xmigratemaniphest.php',1786875798,NULL),
('phabricator:20130923.mrename.sql',1786875798,NULL),
('phabricator:20130924.mdraftkey.sql',1786875798,NULL),
('phabricator:20130925.mpolicy.sql',1786875798,NULL),
('phabricator:20130925.xpolicy.sql',1786875798,NULL),
('phabricator:20130926.dcustom.sql',1786875798,NULL),
('phabricator:20130926.dinkeys.sql',1786875798,NULL),
('phabricator:20130926.dinline.php',1786875798,NULL),
('phabricator:20130927.audiomacro.sql',1786875798,NULL),
('phabricator:20130929.filepolicy.sql',1786875798,NULL),
('phabricator:20131004.dxedgekey.sql',1786875798,NULL),
('phabricator:20131004.dxreviewers.php',1786875798,NULL),
('phabricator:20131006.hdisable.sql',1786875798,NULL),
('phabricator:20131010.pstorage.sql',1786875798,NULL),
('phabricator:20131015.cpolicy.sql',1786875798,NULL),
('phabricator:20131020.col1.sql',1786875799,NULL),
('phabricator:20131020.harbormaster.sql',1786875799,NULL),
('phabricator:20131020.pcustom.sql',1786875799,NULL),
('phabricator:20131020.pxaction.sql',1786875799,NULL),
('phabricator:20131020.pxactionmig.php',1786875799,NULL),
('phabricator:20131025.repopush.sql',1786875799,NULL),
('phabricator:20131026.commitstatus.sql',1786875799,NULL),
('phabricator:20131030.repostatusmessage.sql',1786875799,NULL),
('phabricator:20131031.vcspassword.sql',1786875799,NULL),
('phabricator:20131105.buildstep.sql',1786875799,NULL),
('phabricator:20131106.diffphid.1.col.sql',1786875799,NULL),
('phabricator:20131106.diffphid.2.mig.php',1786875799,NULL),
('phabricator:20131106.diffphid.3.key.sql',1786875799,NULL),
('phabricator:20131106.nuance-v0.sql',1786875799,NULL),
('phabricator:20131107.buildlog.sql',1786875799,NULL),
('phabricator:20131112.userverified.1.col.sql',1786875799,NULL),
('phabricator:20131112.userverified.2.mig.php',1786875799,NULL),
('phabricator:20131118.ownerorder.php',1786875799,NULL),
('phabricator:20131119.passphrase.sql',1786875800,NULL),
('phabricator:20131120.nuancesourcetype.sql',1786875800,NULL),
('phabricator:20131121.passphraseedge.sql',1786875800,NULL),
('phabricator:20131121.repocredentials.1.col.sql',1786875800,NULL),
('phabricator:20131121.repocredentials.2.mig.php',1786875800,NULL),
('phabricator:20131122.repomirror.sql',1786875800,NULL),
('phabricator:20131123.drydockblueprintpolicy.sql',1786875800,NULL),
('phabricator:20131129.drydockresourceblueprint.sql',1786875800,NULL),
('phabricator:20131204.pushlog.sql',1786875800,NULL),
('phabricator:20131205.buildsteporder.sql',1786875800,NULL),
('phabricator:20131205.buildstepordermig.php',1786875800,NULL),
('phabricator:20131205.buildtargets.sql',1786875800,NULL),
('phabricator:20131217.pushlogphid.1.col.sql',1786875800,NULL),
('phabricator:20131219.pxdrop.sql',1786875800,NULL),
('phabricator:20131224.harbormanual.sql',1786875800,NULL),
('phabricator:20131227.heraldobject.sql',1786875800,NULL),
('phabricator:20131231.dropshortcut.sql',1786875800,NULL),
('phabricator:20131302.maniphestvalue.sql',1786875794,NULL),
('phabricator:20140104.harbormastercmd.sql',1786875800,NULL),
('phabricator:20140106.macromailkey.1.sql',1786875800,NULL),
('phabricator:20140106.macromailkey.2.php',1786875800,NULL),
('phabricator:20140108.ddbpname.1.sql',1786875801,NULL),
('phabricator:20140108.ddbpname.2.php',1786875801,NULL),
('phabricator:20140109.ddxactions.sql',1786875801,NULL),
('phabricator:20140109.projectcolumnsdates.sql',1786875801,NULL),
('phabricator:20140113.legalpadsig.1.sql',1786875801,NULL),
('phabricator:20140113.legalpadsig.2.php',1786875801,NULL),
('phabricator:20140115.auth.1.id.sql',1786875801,NULL),
('phabricator:20140115.auth.2.expires.sql',1786875801,NULL),
('phabricator:20140115.auth.3.unlimit.php',1786875801,NULL),
('phabricator:20140115.legalpadsigkey.sql',1786875801,NULL),
('phabricator:20140116.reporefcursor.sql',1786875801,NULL),
('phabricator:20140126.diff.1.parentrevisionid.sql',1786875801,NULL),
('phabricator:20140126.diff.2.repositoryphid.sql',1786875801,NULL),
('phabricator:20140129.dashboard.0.db',1786875801,NULL),
('phabricator:20140130.dash.1.board.sql',1786875801,NULL),
('phabricator:20140130.dash.2.panel.sql',1786875801,NULL),
('phabricator:20140130.dash.3.boardxaction.sql',1786875801,NULL),
('phabricator:20140130.dash.4.panelxaction.sql',1786875801,NULL),
('phabricator:20140130.mail.1.retry.sql',1786875801,NULL),
('phabricator:20140130.mail.2.next.sql',1786875801,NULL),
('phabricator:20140201.gc.1.mailsent.sql',1786875801,NULL),
('phabricator:20140201.gc.2.mailreceived.sql',1786875801,NULL),
('phabricator:20140205.cal.1.rename.sql',1786875801,NULL),
('phabricator:20140205.cal.2.phid-col.sql',1786875801,NULL),
('phabricator:20140205.cal.3.phid-mig.php',1786875801,NULL),
('phabricator:20140205.cal.4.phid-key.sql',1786875801,NULL),
('phabricator:20140210.herald.rule-condition-mig.php',1786875801,NULL),
('phabricator:20140210.projcfield.1.blurb.php',1786875801,NULL),
('phabricator:20140210.projcfield.2.piccol.sql',1786875801,NULL),
('phabricator:20140210.projcfield.3.picmig.sql',1786875801,NULL),
('phabricator:20140210.projcfield.4.memmig.sql',1786875801,NULL),
('phabricator:20140210.projcfield.5.dropprofile.sql',1786875801,NULL),
('phabricator:20140211.dx.1.nullablechangesetid.sql',1786875801,NULL),
('phabricator:20140211.dx.2.migcommenttext.php',1786875801,NULL),
('phabricator:20140211.dx.3.migsubscriptions.sql',1786875801,NULL),
('phabricator:20140211.dx.999.drop.relationships.sql',1786875801,NULL),
('phabricator:20140212.dx.1.armageddon.php',1786875801,NULL),
('phabricator:20140214.clean.1.legacycommentid.sql',1786875801,NULL),
('phabricator:20140214.clean.2.dropcomment.sql',1786875802,NULL),
('phabricator:20140214.clean.3.dropinline.sql',1786875802,NULL),
('phabricator:20140218.differentialdraft.sql',1786875802,NULL),
('phabricator:20140218.passwords.1.extend.sql',1786875802,NULL),
('phabricator:20140218.passwords.2.prefix.sql',1786875802,NULL),
('phabricator:20140218.passwords.3.vcsextend.sql',1786875802,NULL),
('phabricator:20140218.passwords.4.vcs.php',1786875802,NULL),
('phabricator:20140223.bigutf8scratch.sql',1786875802,NULL),
('phabricator:20140224.dxclean.1.datecommitted.sql',1786875802,NULL),
('phabricator:20140226.dxcustom.1.fielddata.php',1786875802,NULL),
('phabricator:20140226.dxcustom.99.drop.sql',1786875802,NULL),
('phabricator:20140228.dxcomment.1.sql',1786875802,NULL),
('phabricator:20140305.diviner.1.slugcol.sql',1786875802,NULL),
('phabricator:20140305.diviner.2.slugkey.sql',1786875802,NULL),
('phabricator:20140311.mdroplegacy.sql',1786875802,NULL),
('phabricator:20140314.projectcolumn.1.statuscol.sql',1786875802,NULL),
('phabricator:20140314.projectcolumn.2.statuskey.sql',1786875802,NULL),
('phabricator:20140317.mupdatedkey.sql',1786875802,NULL),
('phabricator:20140321.harbor.1.bxaction.sql',1786875802,NULL),
('phabricator:20140321.mstatus.1.col.sql',1786875802,NULL),
('phabricator:20140321.mstatus.2.mig.php',1786875802,NULL),
('phabricator:20140323.harbor.1.renames.php',1786875802,NULL),
('phabricator:20140323.harbor.2.message.sql',1786875802,NULL),
('phabricator:20140325.push.1.event.sql',1786875802,NULL),
('phabricator:20140325.push.2.eventphid.sql',1786875802,NULL),
('phabricator:20140325.push.3.groups.php',1786875802,NULL),
('phabricator:20140325.push.4.prune.sql',1786875802,NULL),
('phabricator:20140326.project.1.colxaction.sql',1786875802,NULL),
('phabricator:20140330.flagtext.sql',1786875802,NULL),
('phabricator:20140402.actionlog.sql',1786875802,NULL),
('phabricator:20140410.accountsecret.1.sql',1786875802,NULL),
('phabricator:20140410.accountsecret.2.php',1786875802,NULL),
('phabricator:20140416.harbor.1.sql',1786875802,NULL),
('phabricator:20140421.slowvotecolumnsisclosed.sql',1786875803,NULL),
('phabricator:20140423.session.1.hisec.sql',1786875803,NULL),
('phabricator:20140427.mfactor.1.sql',1786875803,NULL),
('phabricator:20140430.auth.1.partial.sql',1786875803,NULL),
('phabricator:20140430.dash.1.paneltype.sql',1786875803,NULL),
('phabricator:20140430.dash.2.edge.sql',1786875803,NULL),
('phabricator:20140501.passphraselockcredential.sql',1786875803,NULL),
('phabricator:20140501.remove.1.dlog.sql',1786875803,NULL),
('phabricator:20140507.smstable.sql',1786875803,NULL),
('phabricator:20140509.coverage.1.sql',1786875803,NULL),
('phabricator:20140509.dashboardlayoutconfig.sql',1786875803,NULL),
('phabricator:20140512.dparents.1.sql',1786875803,NULL),
('phabricator:20140514.harbormasterbuildabletransaction.sql',1786875803,NULL),
('phabricator:20140514.pholiomockclose.sql',1786875803,NULL),
('phabricator:20140515.trust-emails.sql',1786875803,NULL),
('phabricator:20140517.dxbinarycache.sql',1786875803,NULL),
('phabricator:20140518.dxmorebinarycache.sql',1786875803,NULL),
('phabricator:20140519.dashboardinstall.sql',1786875803,NULL),
('phabricator:20140520.authtemptoken.sql',1786875803,NULL),
('phabricator:20140521.projectslug.1.create.sql',1786875803,NULL),
('phabricator:20140521.projectslug.2.mig.php',1786875803,NULL),
('phabricator:20140522.projecticon.sql',1786875803,NULL),
('phabricator:20140524.auth.mfa.cache.sql',1786875803,NULL),
('phabricator:20140525.hunkmodern.sql',1786875803,NULL),
('phabricator:20140615.pholioedit.1.sql',1786875803,NULL),
('phabricator:20140615.pholioedit.2.sql',1786875803,NULL),
('phabricator:20140617.daemon.explicit-argv.sql',1786875803,NULL),
('phabricator:20140617.daemonlog.sql',1786875803,NULL),
('phabricator:20140624.projcolor.1.sql',1786875803,NULL),
('phabricator:20140624.projcolor.2.sql',1786875803,NULL),
('phabricator:20140629.dasharchive.1.sql',1786875803,NULL),
('phabricator:20140629.legalsig.1.sql',1786875803,NULL),
('phabricator:20140629.legalsig.2.php',1786875803,NULL),
('phabricator:20140701.legalexemption.1.sql',1786875803,NULL),
('phabricator:20140701.legalexemption.2.sql',1786875803,NULL),
('phabricator:20140703.legalcorp.1.sql',1786875803,NULL),
('phabricator:20140703.legalcorp.2.sql',1786875803,NULL),
('phabricator:20140703.legalcorp.3.sql',1786875804,NULL),
('phabricator:20140703.legalcorp.4.sql',1786875804,NULL),
('phabricator:20140703.legalcorp.5.sql',1786875804,NULL),
('phabricator:20140704.harbormasterstep.1.sql',1786875804,NULL),
('phabricator:20140704.harbormasterstep.2.sql',1786875804,NULL),
('phabricator:20140704.legalpreamble.1.sql',1786875804,NULL),
('phabricator:20140706.harbormasterdepend.1.php',1786875804,NULL),
('phabricator:20140706.pedge.1.sql',1786875804,NULL),
('phabricator:20140711.pnames.1.sql',1786875804,NULL),
('phabricator:20140711.pnames.2.php',1786875804,NULL),
('phabricator:20140711.workerpriority.sql',1786875804,NULL),
('phabricator:20140712.projcoluniq.sql',1786875804,NULL),
('phabricator:20140721.phortune.1.cart.sql',1786875804,NULL),
('phabricator:20140721.phortune.2.purchase.sql',1786875804,NULL),
('phabricator:20140721.phortune.3.charge.sql',1786875804,NULL),
('phabricator:20140721.phortune.4.cartstatus.sql',1786875804,NULL),
('phabricator:20140721.phortune.5.cstatusdefault.sql',1786875804,NULL),
('phabricator:20140721.phortune.6.onetimecharge.sql',1786875804,NULL),
('phabricator:20140721.phortune.7.nullmethod.sql',1786875804,NULL),
('phabricator:20140722.appname.php',1786875804,NULL),
('phabricator:20140722.audit.1.xactions.sql',1786875804,NULL),
('phabricator:20140722.audit.2.comments.sql',1786875804,NULL),
('phabricator:20140722.audit.3.miginlines.php',1786875804,NULL),
('phabricator:20140722.audit.4.migtext.php',1786875804,NULL),
('phabricator:20140722.renameauth.php',1786875804,NULL),
('phabricator:20140723.apprenamexaction.sql',1786875804,NULL),
('phabricator:20140725.audit.1.migxactions.php',1786875804,NULL),
('phabricator:20140731.audit.1.subscribers.php',1786875804,NULL),
('phabricator:20140731.cancdn.php',1786875804,NULL),
('phabricator:20140731.harbormasterstepdesc.sql',1786875804,NULL),
('phabricator:20140805.boardcol.1.sql',1786875804,NULL),
('phabricator:20140805.boardcol.2.php',1786875804,NULL),
('phabricator:20140807.harbormastertargettime.sql',1786875804,NULL),
('phabricator:20140808.boardprop.1.sql',1786875804,NULL),
('phabricator:20140808.boardprop.2.sql',1786875804,NULL),
('phabricator:20140808.boardprop.3.php',1786875804,NULL),
('phabricator:20140811.blob.1.sql',1786875804,NULL),
('phabricator:20140811.blob.2.sql',1786875804,NULL),
('phabricator:20140812.projkey.1.sql',1786875804,NULL),
('phabricator:20140812.projkey.2.sql',1786875804,NULL),
('phabricator:20140814.passphrasecredentialconduit.sql',1786875804,NULL),
('phabricator:20140815.cancdncase.php',1786875804,NULL),
('phabricator:20140818.harbormasterindex.1.sql',1786875804,NULL),
('phabricator:20140821.harbormasterbuildgen.1.sql',1786875804,NULL),
('phabricator:20140822.daemonenvhash.sql',1786875804,NULL),
('phabricator:20140902.almanac.0.db',1786875804,NULL),
('phabricator:20140902.almanacdevice.1.sql',1786875805,NULL),
('phabricator:20140904.macroattach.php',1786875805,NULL),
('phabricator:20140910.fund.0.db',1786875805,NULL),
('phabricator:20140911.fund.1.initiative.sql',1786875805,NULL),
('phabricator:20140911.fund.2.xaction.sql',1786875805,NULL),
('phabricator:20140911.fund.3.edge.sql',1786875805,NULL),
('phabricator:20140911.fund.4.backer.sql',1786875805,NULL),
('phabricator:20140911.fund.5.backxaction.sql',1786875805,NULL),
('phabricator:20140914.betaproto.php',1786875805,NULL),
('phabricator:20140917.project.canlock.sql',1786875805,NULL),
('phabricator:20140918.schema.1.dropaudit.sql',1786875805,NULL),
('phabricator:20140918.schema.2.dropauditinline.sql',1786875805,NULL),
('phabricator:20140918.schema.3.wipecache.sql',1786875805,NULL),
('phabricator:20140918.schema.4.cachetype.sql',1786875805,NULL),
('phabricator:20140918.schema.5.slowvote.sql',1786875805,NULL),
('phabricator:20140919.schema.01.calstatus.sql',1786875805,NULL),
('phabricator:20140919.schema.02.calname.sql',1786875805,NULL),
('phabricator:20140919.schema.03.dropaux.sql',1786875805,NULL),
('phabricator:20140919.schema.04.droptaskproj.sql',1786875805,NULL),
('phabricator:20140926.schema.03.dropldapinfo.sql',1786875805,NULL),
('phabricator:20140926.schema.04.dropoauthinfo.sql',1786875805,NULL),
('phabricator:20140926.schema.05.dropprojaffil.sql',1786875805,NULL),
('phabricator:20140926.schema.06.dropsubproject.sql',1786875805,NULL),
('phabricator:20140926.schema.07.droppondcom.sql',1786875805,NULL),
('phabricator:20140927.schema.01.dropsearchq.sql',1786875805,NULL),
('phabricator:20140927.schema.02.pholio1.sql',1786875805,NULL),
('phabricator:20140927.schema.03.pholio2.sql',1786875805,NULL),
('phabricator:20140927.schema.04.pholio3.sql',1786875805,NULL),
('phabricator:20141001.schema.01.version.sql',1786875805,NULL),
('phabricator:20141001.schema.02.taskmail.sql',1786875805,NULL),
('phabricator:20141002.schema.01.liskcounter.sql',1786875805,NULL),
('phabricator:20141002.schema.02.draftnull.sql',1786875805,NULL),
('phabricator:20141004.currency.01.sql',1786875805,NULL),
('phabricator:20141004.currency.02.sql',1786875805,NULL),
('phabricator:20141004.currency.03.sql',1786875805,NULL),
('phabricator:20141004.currency.04.sql',1786875805,NULL),
('phabricator:20141004.currency.05.sql',1786875805,NULL),
('phabricator:20141004.currency.06.sql',1786875805,NULL),
('phabricator:20141004.harborliskcounter.sql',1786875805,NULL),
('phabricator:20141005.phortuneproduct.sql',1786875805,NULL),
('phabricator:20141006.phortunecart.sql',1786875805,NULL),
('phabricator:20141006.phortunemerchant.sql',1786875805,NULL),
('phabricator:20141006.phortunemerchantx.sql',1786875805,NULL),
('phabricator:20141007.fundmerchant.sql',1786875805,NULL),
('phabricator:20141007.fundrisks.sql',1786875805,NULL),
('phabricator:20141007.fundtotal.sql',1786875805,NULL),
('phabricator:20141007.phortunecartmerchant.sql',1786875805,NULL),
('phabricator:20141007.phortunecharge.sql',1786875805,NULL),
('phabricator:20141007.phortunepayment.sql',1786875805,NULL),
('phabricator:20141007.phortuneprovider.sql',1786875805,NULL),
('phabricator:20141007.phortuneproviderx.sql',1786875805,NULL),
('phabricator:20141008.phortunemerchdesc.sql',1786875805,NULL),
('phabricator:20141008.phortuneprovdis.sql',1786875805,NULL),
('phabricator:20141008.phortunerefund.sql',1786875805,NULL),
('phabricator:20141010.fundmailkey.sql',1786875805,NULL),
('phabricator:20141011.phortunemerchedit.sql',1786875805,NULL),
('phabricator:20141012.phortunecartxaction.sql',1786875805,NULL),
('phabricator:20141013.phortunecartkey.sql',1786875805,NULL),
('phabricator:20141016.almanac.device.sql',1786875805,NULL),
('phabricator:20141016.almanac.dxaction.sql',1786875805,NULL),
('phabricator:20141016.almanac.interface.sql',1786875805,NULL),
('phabricator:20141016.almanac.network.sql',1786875805,NULL),
('phabricator:20141016.almanac.nxaction.sql',1786875805,NULL),
('phabricator:20141016.almanac.service.sql',1786875805,NULL),
('phabricator:20141016.almanac.sxaction.sql',1786875805,NULL),
('phabricator:20141017.almanac.binding.sql',1786875806,NULL),
('phabricator:20141017.almanac.bxaction.sql',1786875806,NULL),
('phabricator:20141025.phriction.1.xaction.sql',1786875806,NULL),
('phabricator:20141025.phriction.2.xaction.sql',1786875806,NULL),
('phabricator:20141025.phriction.mailkey.sql',1786875806,NULL),
('phabricator:20141103.almanac.1.delprop.sql',1786875806,NULL),
('phabricator:20141103.almanac.2.addprop.sql',1786875806,NULL),
('phabricator:20141104.almanac.3.edge.sql',1786875806,NULL),
('phabricator:20141105.ssh.1.rename.sql',1786875806,NULL),
('phabricator:20141106.dropold.sql',1786875806,NULL),
('phabricator:20141106.uniqdrafts.php',1786875806,NULL),
('phabricator:20141107.phriction.policy.1.sql',1786875806,NULL),
('phabricator:20141107.phriction.policy.2.php',1786875806,NULL),
('phabricator:20141107.phriction.popkeys.php',1786875806,NULL),
('phabricator:20141107.ssh.1.colname.sql',1786875806,NULL),
('phabricator:20141107.ssh.2.keyhash.sql',1786875806,NULL),
('phabricator:20141107.ssh.3.keyindex.sql',1786875806,NULL),
('phabricator:20141107.ssh.4.keymig.php',1786875806,NULL),
('phabricator:20141107.ssh.5.indexnull.sql',1786875806,NULL),
('phabricator:20141107.ssh.6.indexkey.sql',1786875806,NULL),
('phabricator:20141107.ssh.7.colnull.sql',1786875806,NULL),
('phabricator:20141113.auditdupes.php',1786875806,NULL),
('phabricator:20141118.diffxaction.sql',1786875806,NULL),
('phabricator:20141119.commitpedge.sql',1786875806,NULL),
('phabricator:20141119.differential.diff.policy.sql',1786875806,NULL),
('phabricator:20141119.sshtrust.sql',1786875806,NULL),
('phabricator:20141123.taskpriority.1.sql',1786875806,NULL),
('phabricator:20141123.taskpriority.2.sql',1786875806,NULL),
('phabricator:20141210.maniphestsubscribersmig.1.sql',1786875806,NULL),
('phabricator:20141210.maniphestsubscribersmig.2.sql',1786875806,NULL),
('phabricator:20141210.reposervice.sql',1786875806,NULL),
('phabricator:20141212.conduittoken.sql',1786875806,NULL),
('phabricator:20141215.almanacservicetype.sql',1786875806,NULL),
('phabricator:20141217.almanacdevicelock.sql',1786875806,NULL),
('phabricator:20141217.almanaclock.sql',1786875806,NULL),
('phabricator:20141218.maniphestcctxn.php',1786875807,NULL),
('phabricator:20141222.maniphestprojtxn.php',1786875807,NULL),
('phabricator:20141223.daemonloguser.sql',1786875807,NULL),
('phabricator:20141223.daemonobjectphid.sql',1786875807,NULL),
('phabricator:20141230.pasteeditpolicycolumn.sql',1786875807,NULL),
('phabricator:20141230.pasteeditpolicyexisting.sql',1786875807,NULL),
('phabricator:20150102.policyname.php',1786875807,NULL),
('phabricator:20150102.tasksubscriber.sql',1786875807,NULL),
('phabricator:20150105.conpsearch.sql',1786875807,NULL),
('phabricator:20150114.oauthserver.client.policy.sql',1786875807,NULL),
('phabricator:20150115.applicationemails.sql',1786875807,NULL),
('phabricator:20150115.trigger.1.sql',1786875807,NULL),
('phabricator:20150115.trigger.2.sql',1786875807,NULL),
('phabricator:20150116.maniphestapplicationemails.php',1786875807,NULL),
('phabricator:20150120.maniphestdefaultauthor.php',1786875807,NULL),
('phabricator:20150124.subs.1.sql',1786875807,NULL),
('phabricator:20150129.pastefileapplicationemails.php',1786875807,NULL),
('phabricator:20150130.phortune.1.subphid.sql',1786875807,NULL),
('phabricator:20150130.phortune.2.subkey.sql',1786875807,NULL),
('phabricator:20150131.phortune.1.defaultpayment.sql',1786875807,NULL),
('phabricator:20150205.authprovider.autologin.sql',1786875807,NULL),
('phabricator:20150205.daemonenv.sql',1786875807,NULL),
('phabricator:20150209.invite.sql',1786875807,NULL),
('phabricator:20150209.oauthclient.trust.sql',1786875807,NULL),
('phabricator:20150210.invitephid.sql',1786875807,NULL),
('phabricator:20150212.legalpad.session.1.sql',1786875807,NULL),
('phabricator:20150212.legalpad.session.2.sql',1786875807,NULL),
('phabricator:20150219.scratch.nonmutable.sql',1786875807,NULL),
('phabricator:20150223.daemon.1.id.sql',1786875807,NULL),
('phabricator:20150223.daemon.2.idlegacy.sql',1786875807,NULL),
('phabricator:20150223.daemon.3.idkey.sql',1786875807,NULL),
('phabricator:20150312.filechunk.1.sql',1786875807,NULL),
('phabricator:20150312.filechunk.2.sql',1786875807,NULL),
('phabricator:20150312.filechunk.3.sql',1786875807,NULL),
('phabricator:20150317.conpherence.isroom.1.sql',1786875807,NULL),
('phabricator:20150317.conpherence.isroom.2.sql',1786875807,NULL),
('phabricator:20150317.conpherence.policy.sql',1786875807,NULL),
('phabricator:20150410.nukeruleedit.sql',1786875807,NULL),
('phabricator:20150420.invoice.1.sql',1786875807,NULL),
('phabricator:20150420.invoice.2.sql',1786875807,NULL),
('phabricator:20150425.isclosed.sql',1786875807,NULL),
('phabricator:20150427.calendar.1.edge.sql',1786875808,NULL),
('phabricator:20150427.calendar.1.xaction.sql',1786875808,NULL),
('phabricator:20150427.calendar.2.xaction.sql',1786875808,NULL),
('phabricator:20150428.calendar.1.iscancelled.sql',1786875808,NULL),
('phabricator:20150428.calendar.1.name.sql',1786875808,NULL),
('phabricator:20150429.calendar.1.invitee.sql',1786875808,NULL),
('phabricator:20150430.calendar.1.policies.sql',1786875808,NULL),
('phabricator:20150430.multimeter.0.db',1786875808,NULL),
('phabricator:20150430.multimeter.1.sql',1786875808,NULL),
('phabricator:20150430.multimeter.2.host.sql',1786875808,NULL),
('phabricator:20150430.multimeter.3.viewer.sql',1786875808,NULL),
('phabricator:20150430.multimeter.4.context.sql',1786875808,NULL),
('phabricator:20150430.multimeter.5.label.sql',1786875808,NULL),
('phabricator:20150501.calendar.1.reply.sql',1786875808,NULL),
('phabricator:20150501.calendar.2.reply.php',1786875808,NULL),
('phabricator:20150501.conpherencepics.sql',1786875808,NULL),
('phabricator:20150503.repositorysymbols.1.sql',1786875808,NULL),
('phabricator:20150503.repositorysymbols.2.php',1786875808,NULL),
('phabricator:20150503.repositorysymbols.3.sql',1786875808,NULL),
('phabricator:20150504.symbolsproject.1.php',1786875808,NULL),
('phabricator:20150504.symbolsproject.2.sql',1786875808,NULL),
('phabricator:20150506.calendarunnamedevents.1.php',1786875808,NULL),
('phabricator:20150507.calendar.1.isallday.sql',1786875808,NULL),
('phabricator:20150513.user.cache.1.sql',1786875808,NULL),
('phabricator:20150514.calendar.status.sql',1786875808,NULL),
('phabricator:20150514.phame.blog.xaction.sql',1786875808,NULL),
('phabricator:20150514.user.cache.2.sql',1786875808,NULL),
('phabricator:20150515.phame.post.xaction.sql',1786875808,NULL),
('phabricator:20150515.project.mailkey.1.sql',1786875808,NULL),
('phabricator:20150515.project.mailkey.2.php',1786875808,NULL),
('phabricator:20150519.calendar.calendaricon.sql',1786875808,NULL),
('phabricator:20150525.diff.hidden.1.sql',1786875808,NULL),
('phabricator:20150526.owners.mailkey.1.sql',1786875808,NULL),
('phabricator:20150526.owners.mailkey.2.php',1786875808,NULL),
('phabricator:20150526.owners.xaction.sql',1786875808,NULL),
('phabricator:20150527.calendar.recurringevents.sql',1786875809,NULL),
('phabricator:20150601.spaces.0.db',1786875809,NULL),
('phabricator:20150601.spaces.1.namespace.sql',1786875809,NULL),
('phabricator:20150601.spaces.2.xaction.sql',1786875809,NULL),
('phabricator:20150602.mlist.1.sql',1786875809,NULL),
('phabricator:20150602.mlist.2.php',1786875809,NULL),
('phabricator:20150604.spaces.1.sql',1786875809,NULL),
('phabricator:20150605.diviner.edges.sql',1786875809,NULL),
('phabricator:20150605.diviner.editPolicy.sql',1786875809,NULL),
('phabricator:20150605.diviner.xaction.sql',1786875809,NULL),
('phabricator:20150606.mlist.1.php',1786875809,NULL),
('phabricator:20150609.inline.sql',1786875809,NULL),
('phabricator:20150609.spaces.1.pholio.sql',1786875809,NULL),
('phabricator:20150609.spaces.2.maniphest.sql',1786875809,NULL),
('phabricator:20150610.spaces.1.desc.sql',1786875809,NULL),
('phabricator:20150610.spaces.2.edge.sql',1786875809,NULL),
('phabricator:20150610.spaces.3.archive.sql',1786875809,NULL),
('phabricator:20150611.spaces.1.mailxaction.sql',1786875809,NULL),
('phabricator:20150611.spaces.2.appmail.sql',1786875809,NULL),
('phabricator:20150616.divinerrepository.sql',1786875809,NULL),
('phabricator:20150617.harbor.1.lint.sql',1786875809,NULL),
('phabricator:20150617.harbor.2.unit.sql',1786875809,NULL),
('phabricator:20150618.harbor.1.planauto.sql',1786875809,NULL),
('phabricator:20150618.harbor.2.stepauto.sql',1786875809,NULL),
('phabricator:20150618.harbor.3.buildauto.sql',1786875809,NULL),
('phabricator:20150619.conpherencerooms.1.sql',1786875809,NULL),
('phabricator:20150619.conpherencerooms.2.sql',1786875809,NULL),
('phabricator:20150619.conpherencerooms.3.sql',1786875809,NULL),
('phabricator:20150621.phrase.1.sql',1786875809,NULL),
('phabricator:20150621.phrase.2.sql',1786875809,NULL),
('phabricator:20150622.bulk.1.job.sql',1786875809,NULL),
('phabricator:20150622.bulk.2.task.sql',1786875809,NULL),
('phabricator:20150622.bulk.3.xaction.sql',1786875809,NULL),
('phabricator:20150622.bulk.4.edge.sql',1786875809,NULL),
('phabricator:20150622.metamta.1.phid-col.sql',1786875809,NULL),
('phabricator:20150622.metamta.2.phid-mig.php',1786875809,NULL),
('phabricator:20150622.metamta.3.phid-key.sql',1786875809,NULL),
('phabricator:20150622.metamta.4.actor-phid-col.sql',1786875810,NULL),
('phabricator:20150622.metamta.5.actor-phid-mig.php',1786875810,NULL),
('phabricator:20150622.metamta.6.actor-phid-key.sql',1786875810,NULL),
('phabricator:20150624.spaces.1.repo.sql',1786875810,NULL),
('phabricator:20150626.spaces.1.calendar.sql',1786875810,NULL),
('phabricator:20150630.herald.1.sql',1786875810,NULL),
('phabricator:20150630.herald.2.sql',1786875810,NULL),
('phabricator:20150701.herald.1.sql',1786875810,NULL),
('phabricator:20150701.herald.2.sql',1786875810,NULL),
('phabricator:20150702.spaces.1.slowvote.sql',1786875810,NULL),
('phabricator:20150706.herald.1.sql',1786875810,NULL),
('phabricator:20150707.herald.1.sql',1786875810,NULL),
('phabricator:20150708.arcanistproject.sql',1786875810,NULL),
('phabricator:20150708.herald.1.sql',1786875810,NULL),
('phabricator:20150708.herald.2.sql',1786875810,NULL),
('phabricator:20150708.herald.3.sql',1786875810,NULL),
('phabricator:20150712.badges.0.db',1786875810,NULL),
('phabricator:20150712.badges.1.sql',1786875810,NULL),
('phabricator:20150714.spaces.countdown.1.sql',1786875810,NULL),
('phabricator:20150717.herald.1.sql',1786875810,NULL),
('phabricator:20150719.countdown.1.sql',1786875810,NULL),
('phabricator:20150719.countdown.2.sql',1786875810,NULL),
('phabricator:20150719.countdown.3.sql',1786875810,NULL),
('phabricator:20150721.phurl.0.db',1786875810,NULL),
('phabricator:20150721.phurl.1.url.sql',1786875810,NULL),
('phabricator:20150721.phurl.2.xaction.sql',1786875810,NULL),
('phabricator:20150721.phurl.3.xactioncomment.sql',1786875810,NULL),
('phabricator:20150721.phurl.4.url.sql',1786875810,NULL),
('phabricator:20150721.phurl.5.edge.sql',1786875810,NULL),
('phabricator:20150721.phurl.6.alias.sql',1786875810,NULL),
('phabricator:20150721.phurl.7.authorphid.sql',1786875810,NULL),
('phabricator:20150722.dashboard.1.sql',1786875810,NULL),
('phabricator:20150722.dashboard.2.sql',1786875810,NULL),
('phabricator:20150723.countdown.1.sql',1786875810,NULL),
('phabricator:20150724.badges.comments.1.sql',1786875810,NULL),
('phabricator:20150724.countdown.comments.1.sql',1786875810,NULL),
('phabricator:20150725.badges.mailkey.1.sql',1786875810,NULL),
('phabricator:20150725.badges.mailkey.2.php',1786875810,NULL),
('phabricator:20150725.badges.viewpolicy.3.sql',1786875810,NULL),
('phabricator:20150725.countdown.mailkey.1.sql',1786875810,NULL),
('phabricator:20150725.countdown.mailkey.2.php',1786875810,NULL),
('phabricator:20150725.slowvote.mailkey.1.sql',1786875810,NULL),
('phabricator:20150725.slowvote.mailkey.2.php',1786875810,NULL),
('phabricator:20150727.heraldaction.1.sql',1786875810,NULL),
('phabricator:20150730.herald.1.sql',1786875810,NULL),
('phabricator:20150730.herald.2.sql',1786875810,NULL),
('phabricator:20150730.herald.3.sql',1786875810,NULL),
('phabricator:20150730.herald.4.sql',1786875810,NULL),
('phabricator:20150730.herald.5.sql',1786875810,NULL),
('phabricator:20150730.herald.6.sql',1786875810,NULL),
('phabricator:20150730.herald.7.sql',1786875810,NULL),
('phabricator:20150803.herald.1.sql',1786875810,NULL),
('phabricator:20150803.herald.2.sql',1786875810,NULL),
('phabricator:20150804.ponder.answer.mailkey.1.sql',1786875810,NULL),
('phabricator:20150804.ponder.answer.mailkey.2.php',1786875810,NULL),
('phabricator:20150804.ponder.question.1.sql',1786875811,NULL),
('phabricator:20150804.ponder.question.2.sql',1786875811,NULL),
('phabricator:20150804.ponder.question.3.sql',1786875811,NULL),
('phabricator:20150804.ponder.spaces.4.sql',1786875811,NULL),
('phabricator:20150805.paste.status.1.sql',1786875811,NULL),
('phabricator:20150805.paste.status.2.sql',1786875811,NULL),
('phabricator:20150806.ponder.answer.1.sql',1786875811,NULL),
('phabricator:20150806.ponder.editpolicy.2.sql',1786875811,NULL),
('phabricator:20150806.ponder.status.1.sql',1786875811,NULL),
('phabricator:20150806.ponder.status.2.sql',1786875811,NULL),
('phabricator:20150806.ponder.status.3.sql',1786875811,NULL),
('phabricator:20150808.ponder.vote.1.sql',1786875811,NULL),
('phabricator:20150808.ponder.vote.2.sql',1786875811,NULL),
('phabricator:20150812.ponder.answer.1.sql',1786875811,NULL),
('phabricator:20150812.ponder.answer.2.sql',1786875811,NULL),
('phabricator:20150814.harbormater.artifact.phid.sql',1786875811,NULL),
('phabricator:20150815.owners.status.1.sql',1786875811,NULL),
('phabricator:20150815.owners.status.2.sql',1786875811,NULL),
('phabricator:20150823.nuance.queue.1.sql',1786875811,NULL),
('phabricator:20150823.nuance.queue.2.sql',1786875811,NULL),
('phabricator:20150823.nuance.queue.3.sql',1786875811,NULL),
('phabricator:20150823.nuance.queue.4.sql',1786875811,NULL),
('phabricator:20150828.ponder.wiki.1.sql',1786875811,NULL),
('phabricator:20150829.ponder.dupe.1.sql',1786875811,NULL),
('phabricator:20150904.herald.1.sql',1786875811,NULL),
('phabricator:20150906.mailinglist.sql',1786875811,NULL),
('phabricator:20150910.owners.custom.1.sql',1786875811,NULL),
('phabricator:20150916.drydock.slotlocks.1.sql',1786875811,NULL),
('phabricator:20150922.drydock.commands.1.sql',1786875811,NULL),
('phabricator:20150923.drydock.resourceid.1.sql',1786875811,NULL),
('phabricator:20150923.drydock.resourceid.2.sql',1786875811,NULL),
('phabricator:20150923.drydock.resourceid.3.sql',1786875811,NULL),
('phabricator:20150923.drydock.taskid.1.sql',1786875811,NULL),
('phabricator:20150924.drydock.disable.1.sql',1786875811,NULL),
('phabricator:20150924.drydock.status.1.sql',1786875811,NULL),
('phabricator:20150928.drydock.rexpire.1.sql',1786875811,NULL),
('phabricator:20150930.drydock.log.1.sql',1786875812,NULL),
('phabricator:20151001.drydock.rname.1.sql',1786875812,NULL),
('phabricator:20151002.dashboard.status.1.sql',1786875812,NULL),
('phabricator:20151002.harbormaster.bparam.1.sql',1786875812,NULL),
('phabricator:20151009.drydock.auth.1.sql',1786875812,NULL),
('phabricator:20151010.drydock.auth.2.sql',1786875812,NULL),
('phabricator:20151013.drydock.op.1.sql',1786875812,NULL),
('phabricator:20151023.harborpolicy.1.sql',1786875812,NULL),
('phabricator:20151023.harborpolicy.2.php',1786875812,NULL),
('phabricator:20151023.patchduration.sql',1786875812,25893),
('phabricator:20151030.harbormaster.initiator.sql',1786875812,26041),
('phabricator:20151106.editengine.1.table.sql',1786875812,24007),
('phabricator:20151106.editengine.2.xactions.sql',1786875812,19923),
('phabricator:20151106.phame.post.mailkey.1.sql',1786875812,25952),
('phabricator:20151106.phame.post.mailkey.2.php',1786875812,890),
('phabricator:20151107.phame.blog.mailkey.1.sql',1786875812,25900),
('phabricator:20151107.phame.blog.mailkey.2.php',1786875812,978),
('phabricator:20151108.phame.blog.joinpolicy.sql',1786875812,28904),
('phabricator:20151108.xhpast.stderr.sql',1786875812,57973),
('phabricator:20151109.phame.post.comments.1.sql',1786875812,20992),
('phabricator:20151109.repository.coverage.1.sql',1786875812,410),
('phabricator:20151109.xhpast.db.1.sql',1786875812,13939),
('phabricator:20151109.xhpast.db.2.sql',1786875812,6030),
('phabricator:20151110.daemonenvhash.sql',1786875812,54935),
('phabricator:20151111.phame.blog.archive.1.sql',1786875812,28917),
('phabricator:20151111.phame.blog.archive.2.sql',1786875812,294),
('phabricator:20151112.herald.edge.sql',1786875812,51964),
('phabricator:20151116.owners.edge.sql',1786875812,35913),
('phabricator:20151128.phame.blog.picture.1.sql',1786875813,28901),
('phabricator:20151130.phurl.mailkey.1.sql',1786875813,25976),
('phabricator:20151130.phurl.mailkey.2.php',1786875813,994),
('phabricator:20151202.versioneddraft.1.sql',1786875813,16921),
('phabricator:20151207.editengine.1.sql',1786875813,115987),
('phabricator:20151210.land.1.refphid.sql',1786875813,28926),
('phabricator:20151210.land.2.refphid.php',1786875813,659),
('phabricator:20151215.phame.1.autotitle.sql',1786875813,71907),
('phabricator:20151218.key.1.keyphid.sql',1786875813,28935),
('phabricator:20151218.key.2.keyphid.php',1786875813,412),
('phabricator:20151219.proj.01.prislug.sql',1786875813,27075),
('phabricator:20151219.proj.02.prislugkey.sql',1786875813,29963),
('phabricator:20151219.proj.03.copyslug.sql',1786875813,251),
('phabricator:20151219.proj.04.dropslugkey.sql',1786875813,26981),
('phabricator:20151219.proj.05.dropslug.sql',1786875813,25912),
('phabricator:20151219.proj.06.defaultpolicy.php',1786875813,877),
('phabricator:20151219.proj.07.viewnull.sql',1786875813,50857),
('phabricator:20151219.proj.08.editnull.sql',1786875813,52037),
('phabricator:20151219.proj.09.joinnull.sql',1786875813,52000),
('phabricator:20151219.proj.10.subcolumns.sql',1786875813,165024),
('phabricator:20151219.proj.11.subprojectphids.sql',1786875813,29052),
('phabricator:20151221.search.1.version.sql',1786875813,17019),
('phabricator:20151221.search.2.ownersngrams.sql',1786875813,30648),
('phabricator:20151221.search.3.reindex.php',1786875813,48),
('phabricator:20151223.proj.01.paths.sql',1786875813,40181),
('phabricator:20151223.proj.02.depths.sql',1786875813,29984),
('phabricator:20151223.proj.03.pathkey.sql',1786875814,29899),
('phabricator:20151223.proj.04.keycol.sql',1786875814,28919),
('phabricator:20151223.proj.05.updatekeys.php',1786875814,470),
('phabricator:20151223.proj.06.uniq.sql',1786875814,30120),
('phabricator:20151226.reop.1.sql',1786875814,26041),
('phabricator:20151227.proj.01.materialize.sql',1786875814,313),
('phabricator:20151231.proj.01.icon.php',1786875814,1563),
('phabricator:20160102.badges.award.sql',1786875814,21052),
('phabricator:20160110.repo.01.slug.sql',1786875814,55975),
('phabricator:20160110.repo.02.slug.php',1786875814,488),
('phabricator:20160111.repo.01.slugx.sql',1786875814,456),
('phabricator:20160112.repo.01.uri.sql',1786875814,19984),
('phabricator:20160112.repo.02.uri.index.php',1786875814,25),
('phabricator:20160113.propanel.1.storage.sql',1786875814,18058),
('phabricator:20160113.propanel.2.xaction.sql',1786875814,19925),
('phabricator:20160119.project.1.silence.sql',1786875814,404),
('phabricator:20160122.project.1.boarddefault.php',1786875814,631),
('phabricator:20160124.people.1.icon.sql',1786875814,24956),
('phabricator:20160124.people.2.icondefault.sql',1786875814,367),
('phabricator:20160128.repo.1.pull.sql',1786875814,20010),
('phabricator:20160201.revision.properties.1.sql',1786875814,26072),
('phabricator:20160201.revision.properties.2.sql',1786875814,307),
('phabricator:20160202.board.1.proxy.sql',1786875814,26071),
('phabricator:20160202.ipv6.1.sql',1786875814,84036),
('phabricator:20160202.ipv6.2.php',1786875814,1051),
('phabricator:20160206.cover.1.sql',1786875814,26395),
('phabricator:20160208.task.1.sql',1786875814,29929),
('phabricator:20160208.task.2.sql',1786875814,28905),
('phabricator:20160208.task.3.sql',1786875814,29938),
('phabricator:20160212.proj.1.sql',1786875814,25869),
('phabricator:20160212.proj.2.sql',1786875814,303),
('phabricator:20160215.owners.policy.1.sql',1786875814,25975),
('phabricator:20160215.owners.policy.2.sql',1786875814,28924),
('phabricator:20160215.owners.policy.3.sql',1786875814,287),
('phabricator:20160215.owners.policy.4.sql',1786875814,208),
('phabricator:20160218.callsigns.1.sql',1786875814,50948),
('phabricator:20160221.almanac.1.devicen.sql',1786875814,21010),
('phabricator:20160221.almanac.2.devicei.php',1786875814,42),
('phabricator:20160221.almanac.3.servicen.sql',1786875814,26648),
('phabricator:20160221.almanac.4.servicei.php',1786875814,25),
('phabricator:20160221.almanac.5.networkn.sql',1786875814,29996),
('phabricator:20160221.almanac.6.networki.php',1786875814,22),
('phabricator:20160221.almanac.7.namespacen.sql',1786875814,21040),
('phabricator:20160221.almanac.8.namespace.sql',1786875814,23994),
('phabricator:20160221.almanac.9.namespacex.sql',1786875815,20076),
('phabricator:20160222.almanac.1.properties.php',1786875815,962),
('phabricator:20160223.almanac.1.bound.sql',1786875815,25892),
('phabricator:20160223.almanac.2.lockbind.sql',1786875815,272),
('phabricator:20160223.almanac.3.devicelock.sql',1786875815,28983),
('phabricator:20160223.almanac.4.servicelock.sql',1786875815,29065),
('phabricator:20160223.paste.fileedges.php',1786875815,21),
('phabricator:20160225.almanac.1.disablebinding.sql',1786875815,26900),
('phabricator:20160225.almanac.2.stype.sql',1786875815,25830),
('phabricator:20160225.almanac.3.stype.php',1786875815,695),
('phabricator:20160227.harbormaster.1.plann.sql',1786875815,20031),
('phabricator:20160227.harbormaster.2.plani.php',1786875815,22),
('phabricator:20160303.drydock.1.bluen.sql',1786875815,20035),
('phabricator:20160303.drydock.2.bluei.php',1786875815,22),
('phabricator:20160303.drydock.3.edge.sql',1786875815,35944),
('phabricator:20160308.nuance.01.disabled.sql',1786875815,26811),
('phabricator:20160308.nuance.02.cursordata.sql',1786875815,19899),
('phabricator:20160308.nuance.03.sourcen.sql',1786875815,20995),
('phabricator:20160308.nuance.04.sourcei.php',1786875815,30),
('phabricator:20160308.nuance.05.sourcename.sql',1786875815,41921),
('phabricator:20160308.nuance.06.label.sql',1786875815,29085),
('phabricator:20160308.nuance.07.itemtype.sql',1786875815,29983),
('phabricator:20160308.nuance.08.itemkey.sql',1786875815,28985),
('phabricator:20160308.nuance.09.itemcontainer.sql',1786875815,28924),
('phabricator:20160308.nuance.10.itemkeyu.sql',1786875815,369),
('phabricator:20160308.nuance.11.requestor.sql',1786875815,48007),
('phabricator:20160308.nuance.12.queue.sql',1786875815,48881),
('phabricator:20160316.lfs.01.token.resource.sql',1786875815,40929),
('phabricator:20160316.lfs.02.token.user.sql',1786875815,26047),
('phabricator:20160316.lfs.03.token.properties.sql',1786875815,25881),
('phabricator:20160316.lfs.04.token.default.sql',1786875815,279),
('phabricator:20160317.lfs.01.ref.sql',1786875815,17035),
('phabricator:20160321.nuance.01.taskbridge.sql',1786875815,27013),
('phabricator:20160322.nuance.01.itemcommand.sql',1786875815,17936),
('phabricator:20160323.badgemigrate.sql',1786875815,714),
('phabricator:20160329.nuance.01.requestor.sql',1786875815,14014),
('phabricator:20160329.nuance.02.requestorsource.sql',1786875815,18971),
('phabricator:20160329.nuance.03.requestorxaction.sql',1786875815,13886),
('phabricator:20160329.nuance.04.requestorcomment.sql',1786875815,14069),
('phabricator:20160330.badges.migratequality.sql',1786875815,43020),
('phabricator:20160330.badges.qualityxaction.mig.sql',1786875815,1199),
('phabricator:20160331.fund.comments.1.sql',1786875815,136),
('phabricator:20160404.oauth.1.xaction.sql',1786875816,21047),
('phabricator:20160405.oauth.2.disable.sql',1786875816,26940),
('phabricator:20160406.badges.ngrams.php',1786875816,31),
('phabricator:20160406.badges.ngrams.sql',1786875816,19896),
('phabricator:20160406.columns.1.php',1786875816,439),
('phabricator:20160411.repo.1.version.sql',1786875816,17974),
('phabricator:20160418.repouri.1.sql',1786875816,17972),
('phabricator:20160418.repouri.2.sql',1786875816,25928),
('phabricator:20160418.repoversion.1.sql',1786875816,26055),
('phabricator:20160419.pushlog.1.sql',1786875816,28861),
('phabricator:20160424.locks.1.sql',1786875816,26011),
('phabricator:20160426.searchedge.sql',1786875816,35859),
('phabricator:20160428.repo.1.urixaction.sql',1786875816,20014),
('phabricator:20160503.repo.01.lpath.sql',1786875816,26909),
('phabricator:20160503.repo.02.lpathkey.sql',1786875816,32936),
('phabricator:20160503.repo.03.lpathmigrate.php',1786875816,564),
('phabricator:20160503.repo.04.mirrormigrate.php',1786875816,291),
('phabricator:20160503.repo.05.urimigrate.php',1786875816,268),
('phabricator:20160510.repo.01.uriindex.php',1786875816,3134),
('phabricator:20160513.owners.01.autoreview.sql',1786875816,25882),
('phabricator:20160513.owners.02.autoreviewnone.sql',1786875816,208),
('phabricator:20160516.owners.01.dominion.sql',1786875816,25973),
('phabricator:20160516.owners.02.dominionstrong.sql',1786875816,370),
('phabricator:20160517.oauth.01.edge.sql',1786875816,36002),
('phabricator:20160518.ssh.01.activecol.sql',1786875816,29994),
('phabricator:20160518.ssh.02.activeval.sql',1786875816,331),
('phabricator:20160518.ssh.03.activekey.sql',1786875816,29895),
('phabricator:20160519.ssh.01.xaction.sql',1786875816,21013),
('phabricator:20160531.pref.01.xaction.sql',1786875816,20856),
('phabricator:20160531.pref.02.datecreatecol.sql',1786875816,26004),
('phabricator:20160531.pref.03.datemodcol.sql',1786875816,26036),
('phabricator:20160531.pref.04.datecreateval.sql',1786875816,475),
('phabricator:20160531.pref.05.datemodval.sql',1786875816,387),
('phabricator:20160531.pref.06.phidcol.sql',1786875816,26087),
('phabricator:20160531.pref.07.phidval.php',1786875816,725),
('phabricator:20160601.user.01.cache.sql',1786875816,23888),
('phabricator:20160601.user.02.copyprefs.php',1786875816,940),
('phabricator:20160601.user.03.removetime.sql',1786875816,26071),
('phabricator:20160601.user.04.removetranslation.sql',1786875816,26077),
('phabricator:20160601.user.05.removesex.sql',1786875816,26001),
('phabricator:20160603.user.01.removedcenabled.sql',1786875816,25868),
('phabricator:20160603.user.02.removedctab.sql',1786875816,28018),
('phabricator:20160603.user.03.removedcvisible.sql',1786875816,28003),
('phabricator:20160604.user.01.stringmailprefs.php',1786875816,825),
('phabricator:20160604.user.02.removeimagecache.sql',1786875816,28949),
('phabricator:20160605.user.01.prefnulluser.sql',1786875817,40011),
('phabricator:20160605.user.02.prefbuiltin.sql',1786875817,26047),
('phabricator:20160605.user.03.builtinunique.sql',1786875817,32001),
('phabricator:20160616.phame.blog.header.1.sql',1786875817,26983),
('phabricator:20160616.repo.01.oldref.sql',1786875817,17956),
('phabricator:20160617.harbormaster.01.arelease.sql',1786875817,25866),
('phabricator:20160618.phame.blog.subtitle.sql',1786875817,26833),
('phabricator:20160620.phame.blog.parentdomain.2.sql',1786875817,29041),
('phabricator:20160620.phame.blog.parentsite.1.sql',1786875817,29862),
('phabricator:20160623.phame.blog.fulldomain.1.sql',1786875817,28970),
('phabricator:20160623.phame.blog.fulldomain.2.sql',1786875817,443),
('phabricator:20160623.phame.blog.fulldomain.3.sql',1786875817,367),
('phabricator:20160706.phame.blog.parentdomain.2.sql',1786875817,43045),
('phabricator:20160706.phame.blog.parentsite.1.sql',1786875817,42996),
('phabricator:20160707.calendar.01.stub.sql',1786875817,25944),
('phabricator:20160711.files.01.builtin.sql',1786875817,25990),
('phabricator:20160711.files.02.builtinkey.sql',1786875817,32998),
('phabricator:20160713.event.01.host.sql',1786875817,42835),
('phabricator:20160715.event.01.alldayfrom.sql',1786875817,26037),
('phabricator:20160715.event.02.alldayto.sql',1786875817,25978),
('phabricator:20160715.event.03.allday.php',1786875817,52),
('phabricator:20160720.calendar.invitetxn.php',1786875817,715),
('phabricator:20160721.pack.0.db',1786875817,216),
('phabricator:20160721.pack.01.pub.sql',1786875817,20989),
('phabricator:20160721.pack.02.pubxaction.sql',1786875817,21903),
('phabricator:20160721.pack.03.edge.sql',1786875817,35891),
('phabricator:20160721.pack.04.pkg.sql',1786875817,20967),
('phabricator:20160721.pack.05.pkgxaction.sql',1786875817,20056),
('phabricator:20160721.pack.06.version.sql',1786875817,20838),
('phabricator:20160721.pack.07.versionxaction.sql',1786875817,19977),
('phabricator:20160722.pack.01.pubngrams.sql',1786875817,20061),
('phabricator:20160722.pack.02.pkgngrams.sql',1786875817,20967),
('phabricator:20160722.pack.03.versionngrams.sql',1786875817,20888),
('phabricator:20160810.commit.01.summarylength.sql',1786875817,50961),
('phabricator:20160824.connectionlog.sql',1786875817,14909),
('phabricator:20160824.repohint.01.hint.sql',1786875818,18006),
('phabricator:20160824.repohint.02.movebad.php',1786875818,658),
('phabricator:20160824.repohint.03.nukebad.sql',1786875818,14011),
('phabricator:20160825.ponder.sql',1786875818,487),
('phabricator:20160829.pastebin.01.language.sql',1786875818,48105),
('phabricator:20160829.pastebin.02.language.sql',1786875818,442),
('phabricator:20160913.conpherence.topic.1.sql',1786875818,26958),
('phabricator:20160919.repo.messagecount.sql',1786875818,26931),
('phabricator:20160919.repo.messagedefault.sql',1786875818,24039),
('phabricator:20160921.fileexternalrequest.sql',1786875818,23876),
('phabricator:20160927.phurl.ngrams.php',1786875818,51),
('phabricator:20160927.phurl.ngrams.sql',1786875818,20978),
('phabricator:20160928.repo.messagecount.sql',1786875818,336),
('phabricator:20160928.tokentoken.sql',1786875818,23975),
('phabricator:20161003.cal.01.utcepoch.sql',1786875818,78852),
('phabricator:20161003.cal.02.parameters.sql',1786875818,26237),
('phabricator:20161004.cal.01.noepoch.php',1786875818,1404),
('phabricator:20161005.cal.01.rrules.php',1786875818,391),
('phabricator:20161005.cal.02.export.sql',1786875818,21042),
('phabricator:20161005.cal.03.exportxaction.sql',1786875818,21028),
('phabricator:20161005.conpherence.image.1.sql',1786875818,25977),
('phabricator:20161005.conpherence.image.2.php',1786875818,25),
('phabricator:20161011.conpherence.ngrams.php',1786875818,14),
('phabricator:20161011.conpherence.ngrams.sql',1786875818,19945),
('phabricator:20161012.cal.01.import.sql',1786875818,20912),
('phabricator:20161012.cal.02.importxaction.sql',1786875818,19936),
('phabricator:20161012.cal.03.eventimport.sql',1786875818,103893),
('phabricator:20161013.cal.01.importlog.sql',1786875818,17961),
('phabricator:20161016.conpherence.imagephids.sql',1786875818,26026),
('phabricator:20161025.phortune.contact.1.sql',1786875818,176),
('phabricator:20161025.phortune.merchant.image.1.sql',1786875818,244),
('phabricator:20161026.calendar.01.importtriggers.sql',1786875818,51067),
('phabricator:20161027.calendar.01.externalinvitee.sql',1786875818,17983),
('phabricator:20161029.phortune.invoice.1.sql',1786875818,310),
('phabricator:20161031.calendar.01.seriesparent.sql',1786875818,26174),
('phabricator:20161031.calendar.02.notifylog.sql',1786875818,17035),
('phabricator:20161101.calendar.01.noholiday.sql',1786875818,14947),
('phabricator:20161101.calendar.02.removecolumns.sql',1786875819,185935),
('phabricator:20161104.calendar.01.availability.sql',1786875819,26018),
('phabricator:20161104.calendar.02.availdefault.sql',1786875819,301),
('phabricator:20161115.phamepost.01.subtitle.sql',1786875819,25885),
('phabricator:20161115.phamepost.02.header.sql',1786875819,24970),
('phabricator:20161121.cluster.01.hoststate.sql',1786875819,14038),
('phabricator:20161124.search.01.stopwords.sql',1786875819,14966),
('phabricator:20161125.search.01.stemmed.sql',1786875819,19956),
('phabricator:20161130.search.01.manual.sql',1786875819,18028),
('phabricator:20161130.search.02.rebuild.php',1786875819,56),
('phabricator:20161210.dashboards.01.author.sql',1786875819,26037),
('phabricator:20161210.dashboards.02.author.php',1786875819,1091),
('phabricator:20161211.menu.01.itemkey.sql',1786875819,26080),
('phabricator:20161211.menu.02.itemprops.sql',1786875819,26014),
('phabricator:20161211.menu.03.order.sql',1786875819,25995),
('phabricator:20161212.dashboardpanel.01.author.sql',1786875819,26949),
('phabricator:20161212.dashboardpanel.02.author.php',1786875819,853),
('phabricator:20161212.dashboards.01.icon.sql',1786875819,29008),
('phabricator:20161213.diff.01.hunks.php',1786875819,489),
('phabricator:20161216.dashboard.ngram.01.sql',1786875819,40954),
('phabricator:20161216.dashboard.ngram.02.php',1786875819,27),
('phabricator:20170106.menu.01.customphd.sql',1786875819,25947),
('phabricator:20170109.diff.01.commit.sql',1786875819,26051),
('phabricator:20170119.menuitem.motivator.01.php',1786875819,294),
('phabricator:20170131.dashboard.personal.01.php',1786875819,1188),
('phabricator:20170301.subtype.01.col.sql',1786875819,25906),
('phabricator:20170301.subtype.02.default.sql',1786875819,530),
('phabricator:20170301.subtype.03.taskcol.sql',1786875819,27976),
('phabricator:20170301.subtype.04.taskdefault.sql',1786875819,535),
('phabricator:20170303.people.01.avatar.sql',1786875819,52034),
('phabricator:20170313.reviewers.01.sql',1786875819,16928),
('phabricator:20170316.rawfiles.01.php',1786875819,1392),
('phabricator:20170320.reviewers.01.lastaction.sql',1786875819,26007),
('phabricator:20170320.reviewers.02.lastcomment.sql',1786875819,25943),
('phabricator:20170320.reviewers.03.migrate.php',1786875819,744),
('phabricator:20170322.reviewers.04.actor.sql',1786875819,26015),
('phabricator:20170328.reviewers.01.void.sql',1786875819,26862),
('phabricator:20170404.files.retroactive-content-hash.sql',1786875819,56939),
('phabricator:20170406.hmac.01.keystore.sql',1786875819,16948),
('phabricator:20170410.calendar.01.repair.php',1786875819,1029),
('phabricator:20170412.conpherence.01.picturecrop.sql',1786875819,334),
('phabricator:20170413.conpherence.01.recentparty.sql',1786875819,25878),
('phabricator:20170417.files.ngrams.sql',1786875819,19928),
('phabricator:20170418.1.application.01.xaction.sql',1786875820,19772),
('phabricator:20170418.1.application.02.edge.sql',1786875820,34957),
('phabricator:20170418.files.isDeleted.sql',1786875820,24914),
('phabricator:20170419.app.01.table.sql',1786875820,17962),
('phabricator:20170419.thread.01.behind.sql',1786875820,25917),
('phabricator:20170419.thread.02.status.sql',1786875820,44928),
('phabricator:20170419.thread.03.touched.sql',1786875820,45037),
('phabricator:20170424.user.01.verify.php',1786875820,561),
('phabricator:20170427.owners.01.long.sql',1786875820,26055),
('phabricator:20170504.1.slowvote.shuffle.sql',1786875820,39124),
('phabricator:20170522.nuance.01.itemkey.sql',1786875820,47934),
('phabricator:20170524.nuance.01.command.sql',1786875820,78052),
('phabricator:20170524.nuance.02.commandstatus.sql',1786875820,27011),
('phabricator:20170526.dropdifferentialdrafts.sql',1786875820,14055),
('phabricator:20170526.milestones.php',1786875820,33),
('phabricator:20170528.maniphestdupes.php',1786875820,519),
('phabricator:20170612.repository.image.01.sql',1786875820,25891),
('phabricator:20170614.taskstatus.sql',1786875820,64017),
('phabricator:20170725.legalpad.date.01.sql',1786875820,352),
('phabricator:20170811.differential.01.status.php',1786875820,359),
('phabricator:20170811.differential.02.modernstatus.sql',1786875820,581),
('phabricator:20170811.differential.03.modernxaction.php',1786875820,709),
('phabricator:20170814.search.01.qconfig.sql',1786875820,18027),
('phabricator:20170820.phame.01.post.views.sql',1786875820,27037),
('phabricator:20170820.phame.02.post.views.sql',1786875820,264),
('phabricator:20170824.search.01.saved.php',1786875820,615),
('phabricator:20170825.phame.01.post.views.sql',1786875820,30050),
('phabricator:20170828.ferret.01.taskdoc.sql',1786875820,13839),
('phabricator:20170828.ferret.02.taskfield.sql',1786875820,14959),
('phabricator:20170828.ferret.03.taskngrams.sql',1786875820,14040),
('phabricator:20170830.ferret.01.unique.sql',1786875820,40993),
('phabricator:20170830.ferret.02.term.sql',1786875820,25885),
('phabricator:20170905.ferret.01.diff.doc.sql',1786875820,13997),
('phabricator:20170905.ferret.02.diff.field.sql',1786875820,14904),
('phabricator:20170905.ferret.03.diff.ngrams.sql',1786875820,14930),
('phabricator:20170907.ferret.01.user.doc.sql',1786875820,14954),
('phabricator:20170907.ferret.02.user.field.sql',1786875820,14037),
('phabricator:20170907.ferret.03.user.ngrams.sql',1786875820,14940),
('phabricator:20170907.ferret.04.fund.doc.sql',1786875820,144),
('phabricator:20170907.ferret.05.fund.field.sql',1786875820,229),
('phabricator:20170907.ferret.06.fund.ngrams.sql',1786875820,120),
('phabricator:20170907.ferret.07.passphrase.doc.sql',1786875820,13907),
('phabricator:20170907.ferret.08.passphrase.field.sql',1786875821,14014),
('phabricator:20170907.ferret.09.passphrase.ngrams.sql',1786875821,14906),
('phabricator:20170907.ferret.10.owners.doc.sql',1786875821,14958),
('phabricator:20170907.ferret.11.owners.field.sql',1786875821,14947),
('phabricator:20170907.ferret.12.owners.ngrams.sql',1786875821,14055),
('phabricator:20170907.ferret.13.blog.doc.sql',1786875821,13896),
('phabricator:20170907.ferret.14.blog.field.sql',1786875821,14910),
('phabricator:20170907.ferret.15.blog.ngrams.sql',1786875821,14003),
('phabricator:20170907.ferret.16.post.doc.sql',1786875821,14924),
('phabricator:20170907.ferret.17.post.field.sql',1786875821,14930),
('phabricator:20170907.ferret.18.post.ngrams.sql',1786875821,14038),
('phabricator:20170907.ferret.19.project.doc.sql',1786875821,14921),
('phabricator:20170907.ferret.20.project.field.sql',1786875821,14962),
('phabricator:20170907.ferret.21.project.ngrams.sql',1786875821,14025),
('phabricator:20170907.ferret.22.phriction.doc.sql',1786875821,14918),
('phabricator:20170907.ferret.23.phriction.field.sql',1786875821,14028),
('phabricator:20170907.ferret.24.phriction.ngrams.sql',1786875821,14977),
('phabricator:20170907.ferret.25.event.doc.sql',1786875821,14982),
('phabricator:20170907.ferret.26.event.field.sql',1786875821,14884),
('phabricator:20170907.ferret.27.event.ngrams.sql',1786875821,14939),
('phabricator:20170907.ferret.28.mock.doc.sql',1786875821,13967),
('phabricator:20170907.ferret.29.mock.field.sql',1786875821,14922),
('phabricator:20170907.ferret.30.mock.ngrams.sql',1786875821,14050),
('phabricator:20170907.ferret.31.repo.doc.sql',1786875821,13847),
('phabricator:20170907.ferret.32.repo.field.sql',1786875821,13972),
('phabricator:20170907.ferret.33.repo.ngrams.sql',1786875821,13936),
('phabricator:20170907.ferret.34.commit.doc.sql',1786875821,14941),
('phabricator:20170907.ferret.35.commit.field.sql',1786875821,14939),
('phabricator:20170907.ferret.36.commit.ngrams.sql',1786875821,14907),
('phabricator:20170912.ferret.01.activity.php',1786875821,317),
('phabricator:20170914.ref.01.position.sql',1786875821,14961),
('phabricator:20170915.ref.01.migrate.php',1786875821,731),
('phabricator:20170915.ref.02.drop.id.sql',1786875821,29039),
('phabricator:20170915.ref.03.drop.closed.sql',1786875821,28950),
('phabricator:20170915.ref.04.uniq.sql',1786875821,30009),
('phabricator:20170918.ref.01.position.php',1786875821,34173),
('phabricator:20171002.cngram.01.maniphest.sql',1786875821,19970),
('phabricator:20171002.cngram.02.event.sql',1786875821,19944),
('phabricator:20171002.cngram.03.revision.sql',1786875821,20984),
('phabricator:20171002.cngram.04.fund.sql',1786875821,166),
('phabricator:20171002.cngram.05.owners.sql',1786875821,20994),
('phabricator:20171002.cngram.06.passphrase.sql',1786875821,20959),
('phabricator:20171002.cngram.07.blog.sql',1786875821,21023),
('phabricator:20171002.cngram.08.post.sql',1786875821,19912),
('phabricator:20171002.cngram.09.pholio.sql',1786875821,20982),
('phabricator:20171002.cngram.10.phriction.sql',1786875821,19886),
('phabricator:20171002.cngram.11.project.sql',1786875821,21890),
('phabricator:20171002.cngram.12.user.sql',1786875821,21006),
('phabricator:20171002.cngram.13.repository.sql',1786875822,19948),
('phabricator:20171002.cngram.14.commit.sql',1786875822,20945),
('phabricator:20171026.ferret.01.ponder.doc.sql',1786875822,15850),
('phabricator:20171026.ferret.02.ponder.field.sql',1786875822,14034),
('phabricator:20171026.ferret.03.ponder.ngrams.sql',1786875822,15852),
('phabricator:20171026.ferret.04.ponder.cngrams.sql',1786875822,20941),
('phabricator:20171026.ferret.05.ponder.index.php',1786875822,32),
('phabricator:20171101.diff.01.active.sql',1786875822,26074),
('phabricator:20171101.diff.02.populate.php',1786875822,576),
('phabricator:20180119.bulk.01.silent.sql',1786875822,27014),
('phabricator:20180120.auth.01.password.sql',1786875822,13858),
('phabricator:20180120.auth.02.passwordxaction.sql',1786875822,20910),
('phabricator:20180120.auth.03.vcsdata.sql',1786875822,607),
('phabricator:20180120.auth.04.vcsphid.php',1786875822,895),
('phabricator:20180121.auth.01.vcsnuke.sql',1786875822,14003),
('phabricator:20180121.auth.02.passsalt.sql',1786875822,25973),
('phabricator:20180121.auth.03.accountdata.sql',1786875822,378),
('phabricator:20180121.auth.04.accountphid.php',1786875822,366),
('phabricator:20180121.auth.05.accountnuke.sql',1786875822,51881),
('phabricator:20180121.auth.06.legacydigest.sql',1786875822,25951),
('phabricator:20180121.auth.07.marklegacy.sql',1786875822,328),
('phabricator:20180124.herald.01.repetition.sql',1786875822,48346),
('phabricator:20180207.mail.01.task.sql',1786875822,29935),
('phabricator:20180207.mail.02.revision.sql',1786875822,27025),
('phabricator:20180207.mail.03.mock.sql',1786875822,28964),
('phabricator:20180208.maniphest.01.close.sql',1786875822,60892),
('phabricator:20180208.maniphest.02.populate.php',1786875822,594),
('phabricator:20180209.hook.01.hook.sql',1786875822,14938),
('phabricator:20180209.hook.02.hookxaction.sql',1786875822,21008),
('phabricator:20180209.hook.03.hookrequest.sql',1786875822,14921),
('phabricator:20180210.hunk.01.droplegacy.sql',1786875822,14034),
('phabricator:20180210.hunk.02.renamemodern.sql',1786875822,14111),
('phabricator:20180212.harbor.01.receiver.sql',1786875822,38969),
('phabricator:20180214.harbor.01.aborted.php',1786875822,855),
('phabricator:20180215.phriction.01.phidcol.sql',1786875822,26944),
('phabricator:20180215.phriction.02.phidvalues.php',1786875822,719),
('phabricator:20180215.phriction.03.descempty.sql',1786875822,300),
('phabricator:20180215.phriction.04.descnull.sql',1786875822,44912),
('phabricator:20180215.phriction.05.statustext.sql',1786875822,45004),
('phabricator:20180215.phriction.06.statusvalue.sql',1786875822,440),
('phabricator:20180218.fact.01.dim.key.sql',1786875822,18007),
('phabricator:20180218.fact.02.dim.obj.sql',1786875822,16937),
('phabricator:20180218.fact.03.data.int.sql',1786875822,14936),
('phabricator:20180222.log.01.filephid.sql',1786875822,26956),
('phabricator:20180223.log.01.bytelength.sql',1786875823,29963),
('phabricator:20180223.log.02.chunkformat.sql',1786875823,29855),
('phabricator:20180223.log.03.chunkdefault.sql',1786875823,401),
('phabricator:20180223.log.04.linemap.sql',1786875823,28958),
('phabricator:20180223.log.05.linemapdefault.sql',1786875823,298),
('phabricator:20180228.log.01.offset.sql',1786875823,51974),
('phabricator:20180305.lock.01.locklog.sql',1786875823,14941),
('phabricator:20180306.opath.01.digest.sql',1786875823,25943),
('phabricator:20180306.opath.02.digestpopulate.php',1786875823,605),
('phabricator:20180306.opath.03.purge.php',1786875823,329),
('phabricator:20180306.opath.04.unique.sql',1786875823,33102),
('phabricator:20180306.opath.05.longpath.sql',1786875823,41997),
('phabricator:20180306.opath.06.pathdisplay.sql',1786875823,25990),
('phabricator:20180306.opath.07.copypaths.sql',1786875823,218),
('phabricator:20180309.owners.01.primaryowner.sql',1786875823,26089),
('phabricator:20180312.reviewers.01.options.sql',1786875823,25877),
('phabricator:20180312.reviewers.02.optionsdefault.sql',1786875823,223),
('phabricator:20180322.lock.01.identifier.sql',1786875823,59087),
('phabricator:20180322.lock.02.wait.sql',1786875823,77923),
('phabricator:20180326.lock.03.nonunique.sql',1786875823,26951),
('phabricator:20180403.draft.01.broadcast.php',1786875823,846),
('phabricator:20180410.almanac.01.iface.xaction.sql',1786875823,20888),
('phabricator:20180418.alamanc.interface.unique.php',1786875823,36022),
('phabricator:20180418.almanac.network.unique.php',1786875823,33906),
('phabricator:20180419.phlux.edges.sql',1786875823,35954),
('phabricator:20180423.mail.01.properties.sql',1786875823,18043),
('phabricator:20180430.repo_identity.sql',1786875823,19985),
('phabricator:20180504.owners.01.mailkey.php',1786875823,835),
('phabricator:20180504.owners.02.rmkey.sql',1786875823,25949),
('phabricator:20180504.owners.03.properties.sql',1786875823,25926),
('phabricator:20180504.owners.04.default.sql',1786875823,446),
('phabricator:20180504.repo_identity.author.sql',1786875823,25994),
('phabricator:20180504.repo_identity.xaction.sql',1786875823,21921),
('phabricator:20180509.repo_identity.commits.sql',1786875823,26849),
('phabricator:20180730.phriction.01.spaces.sql',1786875823,26087),
('phabricator:20180730.project.01.spaces.sql',1786875823,26817),
('phabricator:20180809.repo_identities.activity.php',1786875823,547),
('phabricator:20180827.drydock.01.acquired.sql',1786875824,26960),
('phabricator:20180827.drydock.02.activated.sql',1786875824,25872),
('phabricator:20180828.phriction.01.contentphid.sql',1786875824,26060),
('phabricator:20180828.phriction.02.documentphid.sql',1786875824,26979),
('phabricator:20180828.phriction.03.editedepoch.sql',1786875824,25861),
('phabricator:20180828.phriction.04.migrate.php',1786875824,597),
('phabricator:20180828.phriction.05.contentid.sql',1786875824,28896),
('phabricator:20180828.phriction.06.c.documentid.php',1786875824,27004),
('phabricator:20180828.phriction.06.documentid.sql',1786875824,29850),
('phabricator:20180828.phriction.07.c.documentuniq.sql',1786875824,364),
('phabricator:20180828.phriction.07.documentkey.sql',1786875824,33077),
('phabricator:20180829.phriction.01.mailkey.php',1786875824,578),
('phabricator:20180829.phriction.02.rmkey.sql',1786875824,29062),
('phabricator:20180830.phriction.01.maxversion.sql',1786875824,29838),
('phabricator:20180830.phriction.02.maxes.php',1786875824,470),
('phabricator:20180910.audit.01.searches.php',1786875824,514),
('phabricator:20180910.audit.02.string.sql',1786875824,51038),
('phabricator:20180910.audit.03.status.php',1786875824,937),
('phabricator:20180910.audit.04.xactions.php',1786875824,874),
('phabricator:20180914.audit.01.mailkey.php',1786875824,235),
('phabricator:20180914.audit.02.rmkey.sql',1786875824,29895),
('phabricator:20180914.drydock.01.operationphid.sql',1786875824,25974),
('phabricator:20181024.drydock.01.commandprops.sql',1786875824,28060),
('phabricator:20181024.drydock.02.commanddefaults.sql',1786875824,435),
('phabricator:20181031.board.01.queryreset.php',1786875824,1618),
('phabricator:20181106.repo.01.sync.sql',1786875824,15012),
('phabricator:20181106.repo.02.hook.sql',1786875824,26031),
('phabricator:20181213.auth.01.sessionphid.sql',1786875824,27961),
('phabricator:20181213.auth.02.populatephid.php',1786875824,614),
('phabricator:20181213.auth.03.phidkey.sql',1786875824,32903),
('phabricator:20181213.auth.04.longerhashes.sql',1786875824,50054),
('phabricator:20181213.auth.05.longerloghashes.sql',1786875824,54013),
('phabricator:20181213.auth.06.challenge.sql',1786875824,14919),
('phabricator:20181214.auth.01.workflowkey.sql',1786875824,26050),
('phabricator:20181217.auth.01.digest.sql',1786875824,26005),
('phabricator:20181217.auth.02.ttl.sql',1786875824,25855),
('phabricator:20181217.auth.03.completed.sql',1786875824,25978),
('phabricator:20181218.pholio.01.imageauthor.sql',1786875824,26942),
('phabricator:20181219.pholio.01.imagephid.sql',1786875824,26930),
('phabricator:20181219.pholio.02.imagemigrate.php',1786875825,888),
('phabricator:20181219.pholio.03.imageid.sql',1786875825,41882),
('phabricator:20181220.pholio.01.mailkey.php',1786875825,654),
('phabricator:20181220.pholio.02.dropmailkey.sql',1786875825,28915),
('phabricator:20181228.auth.01.provider.sql',1786875825,14891),
('phabricator:20181228.auth.02.xaction.sql',1786875825,20950),
('phabricator:20181228.auth.03.name.sql',1786875825,26991),
('phabricator:20190101.sms.01.drop.sql',1786875825,13980),
('phabricator:20190115.mfa.01.provider.sql',1786875825,25902),
('phabricator:20190115.mfa.02.migrate.php',1786875825,701),
('phabricator:20190115.mfa.03.factorkey.sql',1786875825,28970),
('phabricator:20190116.contact.01.number.sql',1786875825,14899),
('phabricator:20190116.contact.02.xaction.sql',1786875825,20979),
('phabricator:20190116.phortune.01.billing.sql',1786875825,272),
('phabricator:20190117.authmessage.01.message.sql',1786875825,14950),
('phabricator:20190117.authmessage.02.xaction.sql',1786875825,21951),
('phabricator:20190121.contact.01.primary.sql',1786875825,25894),
('phabricator:20190127.project.01.subtype.sql',1786875825,25907),
('phabricator:20190127.project.02.default.sql',1786875825,279),
('phabricator:20190129.project.01.spaces.php',1786875825,407),
('phabricator:20190206.external.01.legalpad.sql',1786875825,483),
('phabricator:20190206.external.02.email.sql',1786875825,576),
('phabricator:20190206.external.03.providerphid.sql',1786875825,25858),
('phabricator:20190206.external.04.providerlink.php',1786875825,873),
('phabricator:20190207.packages.01.state.sql',1786875825,26045),
('phabricator:20190207.packages.02.migrate.sql',1786875825,263),
('phabricator:20190207.packages.03.drop.sql',1786875825,25847),
('phabricator:20190207.packages.04.xactions.php',1786875825,665),
('phabricator:20190215.daemons.01.dropdataid.php',1786875825,27010),
('phabricator:20190215.daemons.02.nulldataid.sql',1786875825,51932),
('phabricator:20190215.harbor.01.stringindex.sql',1786875825,16869),
('phabricator:20190215.harbor.02.stringcol.sql',1786875825,26869),
('phabricator:20190220.daemon_worker.completed.01.sql',1786875825,25984),
('phabricator:20190220.daemon_worker.completed.02.sql',1786875825,25969),
('phabricator:20190226.harbor.01.planprops.sql',1786875825,25851),
('phabricator:20190226.harbor.02.planvalue.sql',1786875825,354),
('phabricator:20190307.herald.01.comments.sql',1786875825,14010),
('phabricator:20190312.triggers.01.trigger.sql',1786875825,13993),
('phabricator:20190312.triggers.02.xaction.sql',1786875825,21042),
('phabricator:20190312.triggers.03.triggerphid.sql',1786875825,26024),
('phabricator:20190322.triggers.01.usage.sql',1786875825,16982),
('phabricator:20190329.portals.01.create.sql',1786875825,14899),
('phabricator:20190329.portals.02.xaction.sql',1786875825,20910),
('phabricator:20190410.portals.01.ferret.doc.sql',1786875825,14006),
('phabricator:20190410.portals.02.ferret.field.sql',1786875825,14960),
('phabricator:20190410.portals.03.ferret.ngrams.sql',1786875825,13949),
('phabricator:20190410.portals.04.ferret.cngrams.sql',1786875825,20870),
('phabricator:20190412.dashboard.01.panels.php',1786875826,409),
('phabricator:20190412.dashboard.02.install.sql',1786875826,14001),
('phabricator:20190412.dashboard.03.dashngrams.sql',1786875826,13824),
('phabricator:20190412.dashboard.04.panelngrams.sql',1786875826,14080),
('phabricator:20190412.dashboard.05.dferret.doc.sql',1786875826,13901),
('phabricator:20190412.dashboard.06.dferret.field.sql',1786875826,14954),
('phabricator:20190412.dashboard.07.dferret.ngrams.sql',1786875826,14904),
('phabricator:20190412.dashboard.08.dferret.cngrams.sql',1786875826,21011),
('phabricator:20190412.dashboard.09.pferret.doc.sql',1786875826,14942),
('phabricator:20190412.dashboard.10.pferret.field.sql',1786875826,14968),
('phabricator:20190412.dashboard.11.pferret.ngrams.sql',1786875826,14961),
('phabricator:20190412.dashboard.12.pferret.cngrams.sql',1786875826,20987),
('phabricator:20190412.dashboard.13.rebuild.php',1786875828,16885),
('phabricator:20190412.herald.01.rebuild.php',1786875828,7881),
('phabricator:20190416.chart.01.storage.sql',1786875826,16844),
('phabricator:20190523.myisam.01.documentfield.sql',1786875826,11998),
('phabricator:20190718.paste.0.db',1786875826,128),
('phabricator:20190718.paste.01.edge.sql',1786875826,13966),
('phabricator:20190718.paste.02.edgedata.sql',1786875826,13925),
('phabricator:20190718.paste.03.paste.sql',1786875826,13785),
('phabricator:20190718.paste.04.xaction.sql',1786875826,13975),
('phabricator:20190718.paste.05.comment.sql',1786875826,14971),
('phabricator:20190802.email.01.storage.sql',1786875826,174),
('phabricator:20190802.email.02.xaction.sql',1786875826,170),
('phabricator:20190815.account.01.carts.php',1786875826,36),
('phabricator:20190815.account.02.subscriptions.php',1786875826,10),
('phabricator:20190816.payment.01.xaction.sql',1786875826,164),
('phabricator:20190816.subscription.01.xaction.sql',1786875826,150),
('phabricator:20190822.merchant.01.view.sql',1786875826,277),
('phabricator:20190909.herald.01.rebuild.php',1786875828,9831),
('phabricator:20190924.diffusion.01.permanent.sql',1786875826,25979),
('phabricator:20190924.diffusion.02.default.sql',1786875826,256),
('phabricator:20191028.uriindex.01.rebuild.php',1786875828,6894),
('phabricator:20191113.identity.01.email.sql',1786875826,25934),
('phabricator:20191113.identity.02.populate.php',1786875826,873),
('phabricator:20191113.identity.03.unassigned.sql',1786875826,215),
('phabricator:20191114.email.01.phid.sql',1786875826,25903),
('phabricator:20191114.email.02.populate.php',1786875826,354),
('phabricator:20200220.xaccount.01.sql',1786875826,13957),
('phabricator:20200222.xident.01.migrate.php',1786875826,379),
('phabricator:20200222.xident.02.dropkey.php',1786875826,26951),
('phabricator:20200416.paste.01.ferret.doc.sql',1786875826,14932),
('phabricator:20200416.paste.02.ferret.field.sql',1786875826,14889),
('phabricator:20200416.paste.03.ferret.ngrams.sql',1786875826,13962),
('phabricator:20200416.paste.04.ferret.cngrams.sql',1786875826,20982),
('phabricator:20200417.viewstate.01.storage.sql',1786875826,17899),
('phabricator:20200428.inline.01.differential.column.sql',1786875826,26934),
('phabricator:20200428.inline.02.diffusion.column.sql',1786875826,25861),
('phabricator:20200428.inline.03.differential.value.sql',1786875826,260),
('phabricator:20200428.inline.04.diffusion.value.sql',1786875826,385),
('phabricator:20200520.inline.01.remcache.sql',1786875826,14024),
('phabricator:20200520.inline.02.addcache.sql',1786875826,18907),
('phabricator:20200520.inline.03.dropcommit.sql',1786875826,14029),
('phabricator:20210122.queuecontainer.01.sql',1786875826,52930),
('phabricator:20210215.changeset.01.phid.sql',1786875826,25985),
('phabricator:20210215.changeset.02.phid-populate.php',1786875826,740),
('phabricator:20210216.index.01.version.sql',1786875826,26899),
('phabricator:20210216.index.02.epoch.sql',1786875826,26026),
('phabricator:20210309.auditors.01.status.sql',1786875826,399),
('phabricator:20210315.affectedpath.01.epoch.sql',1786875826,42028),
('phabricator:20210315.affectedpath.02.repositoryid.sql',1786875827,41963),
('phabricator:20210316.almanac.01.device-mailkey.php',1786875827,757),
('phabricator:20210316.almanac.02.device-dropmailkey.sql',1786875827,28762),
('phabricator:20210316.almanac.03.device-status.sql',1786875827,29005),
('phabricator:20210316.almanac.04.device-status-value.sql',1786875827,344),
('phabricator:20210316.almanac.05.service-mailkey.php',1786875827,304),
('phabricator:20210316.almanac.06.service-dropmailkey.sql',1786875827,28958),
('phabricator:20210316.almanac.07.binding-mailkey.php',1786875827,519),
('phabricator:20210316.almanac.08.binding-dropmailkey.sql',1786875827,29844),
('phabricator:20210316.almanac.09.namespace-mailkey.php',1786875827,1024),
('phabricator:20210316.almanac.10.namespace-dropmailkey.sql',1786875827,29767),
('phabricator:20210316.almanac.11.network-mailkey.php',1786875827,410),
('phabricator:20210316.almanac.12.network-dropmailkey.sql',1786875827,28936),
('phabricator:20210316.almanac.13.event-mailkey.php',1786875827,422),
('phabricator:20210316.almanac.14.event-dropmailkey.sql',1786875827,26115),
('phabricator:20210316.almanac.15.intiative-mailkey.php',1786875827,42),
('phabricator:20210316.almanac.16.initiative-dropmailkey.sql',1786875827,166),
('phabricator:20210625.owners.01.authority.sql',1786875827,26071),
('phabricator:20210625.owners.02.authority-default.sql',1786875827,303),
('phabricator:20210713.harborcommand.01.migrate.sql',1786875827,526),
('phabricator:20210713.harborcommand.02.drop.sql',1786875827,13986),
('phabricator:20210715.harborcommand.01.xactions.php',1786875827,577),
('phabricator:20210802.legalpad_document_signature.01.phid.sql',1786875827,26008),
('phabricator:20210802.legalpad_document_signature.02.phid-populate.php',1786875827,895),
('phabricator:20220401.phameinteract.01.sql',1786875827,42991),
('phabricator:20220401.phameinteract.02.sql',1786875827,42102),
('phabricator:20220401.phameinteract.03.sql',1786875827,27127),
('phabricator:20220401.phameinteract.04.postinteract.sql',1786875827,31069),
('phabricator:20220510.file.01.attach.sql',1786875827,14935),
('phabricator:20220519.file.02.migrate.sql',1786875827,666),
('phabricator:20220525.slowvote.01.mailkey.php',1786875827,453),
('phabricator:20220525.slowvote.02.mailkey-drop.sql',1786875827,29039),
('phabricator:20220525.slowvote.03.response-type.sql',1786875827,39126),
('phabricator:20220525.slowvote.04.response-value.sql',1786875827,468),
('phabricator:20220525.slowvote.05.response-xactions.sql',1786875827,885),
('phabricator:20220525.slowvote.06.method-type.sql',1786875827,40017),
('phabricator:20220525.slowvote.07.method-value.sql',1786875827,550),
('phabricator:20220525.slowvote.08.status-type.sql',1786875827,39010),
('phabricator:20220525.slowvote.09.status-value.sql',1786875827,540),
('phabricator:20220525.slowvote.10.status-xactions.sql',1786875827,385),
('phabricator:20230902.repository.01.rebuild-index.php',1786875828,6989),
('phabricator:20230917.fileattachment.01.delete.sql',1786875827,511),
('phabricator:20250227.paste.01.mailkey.php',1786875827,1091),
('phabricator:20250227.paste.02.mailkey.sql',1786875827,28974),
('phabricator:20251024.herald.webhookuri.sql',1786875827,36978),
('phabricator:20251124.phpast.parsetree.sql',1786875827,13941),
('phabricator:20260219.conduit.tokenname.sql',1786875827,26482),
('phabricator:20260221.policy.1.named.sql',1786875827,17982),
('phabricator:20260221.policy.2.xaction.sql',1786875827,20864),
('phabricator:20260221.policy.3.xaction.sql',1786875827,20992),
('phabricator:20260221.policy.4.edges.sql',1786875828,35023),
('phabricator:20260305.repository.mirror.sql',1786875828,13851),
('phabricator:20260813.diviner.atomorder.sql',1786875828,51092),
('phabricator:daemonstatus.sql',1786875792,NULL),
('phabricator:daemonstatuskey.sql',1786875792,NULL),
('phabricator:daemontaskarchive.sql',1786875793,NULL),
('phabricator:db.audit',1786875776,NULL),
('phabricator:db.auth',1786875776,NULL),
('phabricator:db.cache',1786875776,NULL),
('phabricator:db.calendar',1786875776,NULL),
('phabricator:db.conduit',1786875776,NULL),
('phabricator:db.config',1786875776,NULL),
('phabricator:db.conpherence',1786875776,NULL),
('phabricator:db.countdown',1786875776,NULL),
('phabricator:db.daemon',1786875776,NULL),
('phabricator:db.differential',1786875776,NULL),
('phabricator:db.diviner',1786875776,NULL),
('phabricator:db.doorkeeper',1786875776,NULL),
('phabricator:db.draft',1786875776,NULL),
('phabricator:db.drydock',1786875776,NULL),
('phabricator:db.fact',1786875776,NULL),
('phabricator:db.feed',1786875776,NULL),
('phabricator:db.file',1786875776,NULL),
('phabricator:db.flag',1786875776,NULL),
('phabricator:db.harbormaster',1786875776,NULL),
('phabricator:db.herald',1786875776,NULL),
('phabricator:db.legalpad',1786875776,NULL),
('phabricator:db.maniphest',1786875776,NULL),
('phabricator:db.meta_data',1786875776,NULL),
('phabricator:db.metamta',1786875776,NULL),
('phabricator:db.nuance',1786875776,NULL),
('phabricator:db.oauth_server',1786875776,NULL),
('phabricator:db.owners',1786875776,NULL),
('phabricator:db.passphrase',1786875776,NULL),
('phabricator:db.pastebin',1786875776,NULL),
('phabricator:db.phame',1786875776,NULL),
('phabricator:db.phlux',1786875776,NULL),
('phabricator:db.pholio',1786875776,NULL),
('phabricator:db.phrequent',1786875776,NULL),
('phabricator:db.phriction',1786875776,NULL),
('phabricator:db.policy',1786875776,NULL),
('phabricator:db.ponder',1786875776,NULL),
('phabricator:db.project',1786875776,NULL),
('phabricator:db.repository',1786875776,NULL),
('phabricator:db.search',1786875776,NULL),
('phabricator:db.slowvote',1786875776,NULL),
('phabricator:db.timeline',1786875776,NULL),
('phabricator:db.token',1786875776,NULL),
('phabricator:db.user',1786875776,NULL),
('phabricator:db.worker',1786875776,NULL),
('phabricator:db.xhpast',1786875776,NULL),
('phabricator:db.xhpastview',1786875776,NULL),
('phabricator:db.xhprof',1786875776,NULL),
('phabricator:differentialbookmarks.sql',1786875791,NULL),
('phabricator:draft-metadata.sql',1786875792,NULL),
('phabricator:dropfileproxyimage.sql',1786875793,NULL),
('phabricator:drydockresoucetype.sql',1786875793,NULL),
('phabricator:drydocktaskid.sql',1786875793,NULL),
('phabricator:edgetype.sql',1786875792,NULL),
('phabricator:emailtable.sql',1786875791,NULL),
('phabricator:emailtableport.sql',1786875791,NULL),
('phabricator:emailtableremove.sql',1786875791,NULL),
('phabricator:fact-raw.sql',1786875792,NULL),
('phabricator:harbormasterobject.sql',1786875791,NULL),
('phabricator:holidays.sql',1786875791,NULL),
('phabricator:ldapinfo.sql',1786875791,NULL),
('phabricator:legalpad-mailkey-populate.php',1786875796,NULL),
('phabricator:legalpad-mailkey.sql',1786875796,NULL),
('phabricator:liskcounters-task.sql',1786875793,NULL),
('phabricator:liskcounters.php',1786875793,NULL),
('phabricator:liskcounters.sql',1786875793,NULL),
('phabricator:maniphestxcache.sql',1786875791,NULL),
('phabricator:markupcache.sql',1786875791,NULL),
('phabricator:migrate-differential-dependencies.php',1786875791,NULL),
('phabricator:migrate-maniphest-dependencies.php',1786875791,NULL),
('phabricator:migrate-maniphest-revisions.php',1786875792,NULL),
('phabricator:migrate-project-edges.php',1786875792,NULL),
('phabricator:owners-exclude.sql',1786875793,NULL),
('phabricator:pastepolicy.sql',1786875792,NULL),
('phabricator:phameblog.sql',1786875792,NULL),
('phabricator:phamedomain.sql',1786875792,NULL),
('phabricator:phameoneblog.sql',1786875793,NULL),
('phabricator:phamepolicy.sql',1786875793,NULL),
('phabricator:phiddrop.sql',1786875791,NULL),
('phabricator:pholio.sql',1786875793,NULL),
('phabricator:policy-project.sql',1786875792,NULL),
('phabricator:ponder-comments.sql',1786875792,NULL),
('phabricator:ponder-mailkey-populate.php',1786875792,NULL),
('phabricator:ponder-mailkey.sql',1786875792,NULL),
('phabricator:ponder.sql',1786875792,NULL),
('phabricator:repository-lint.sql',1786875793,NULL),
('phabricator:statustxt.sql',1786875793,NULL),
('phabricator:symbolcontexts.sql',1786875792,NULL),
('phabricator:testdatabase.sql',1786875791,NULL),
('phabricator:threadtopic.sql',1786875791,NULL),
('phabricator:userstatus.sql',1786875791,NULL),
('phabricator:usertranslation.sql',1786875791,NULL),
('phabricator:xhprof.sql',1786875792,NULL);

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_metamta` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_metamta`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_metamta`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_metamta`;

CREATE TABLE `metamta_applicationemail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `applicationPHID` varbinary(64) NOT NULL,
  `address` varchar(128) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `configData` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_address` (`address`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_application` (`applicationPHID`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_metamta`;

CREATE TABLE `metamta_applicationemailtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_metamta`;

CREATE TABLE `metamta_mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `actorPHID` varbinary(64) DEFAULT NULL,
  `parameters` longtext NOT NULL,
  `status` varchar(32) NOT NULL,
  `message` longtext DEFAULT NULL,
  `relatedPHID` varbinary(64) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `relatedPHID` (`relatedPHID`),
  KEY `key_created` (`dateCreated`),
  KEY `key_actorPHID` (`actorPHID`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_metamta`;

CREATE TABLE `metamta_mailproperties` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `mailProperties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_metamta`;

CREATE TABLE `metamta_receivedmail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `headers` longtext NOT NULL,
  `bodies` longtext NOT NULL,
  `attachments` longtext NOT NULL,
  `relatedPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `message` longtext DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `messageIDHash` binary(12) NOT NULL,
  `status` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `relatedPHID` (`relatedPHID`),
  KEY `authorPHID` (`authorPHID`),
  KEY `key_messageIDHash` (`messageIDHash`),
  KEY `key_created` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_multimeter` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_multimeter`;

CREATE TABLE `multimeter_context` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `nameHash` binary(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_hash` (`nameHash`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_multimeter`;

CREATE TABLE `multimeter_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventType` int(10) unsigned NOT NULL,
  `eventLabelID` int(10) unsigned NOT NULL,
  `resourceCost` bigint(20) NOT NULL,
  `sampleRate` int(10) unsigned NOT NULL,
  `eventContextID` int(10) unsigned NOT NULL,
  `eventHostID` int(10) unsigned NOT NULL,
  `eventViewerID` int(10) unsigned NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  `requestKey` binary(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_request` (`requestKey`),
  KEY `key_type` (`eventType`,`epoch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_multimeter`;

CREATE TABLE `multimeter_host` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `nameHash` binary(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_hash` (`nameHash`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_multimeter`;

CREATE TABLE `multimeter_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `nameHash` binary(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_hash` (`nameHash`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_multimeter`;

CREATE TABLE `multimeter_viewer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `nameHash` binary(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_hash` (`nameHash`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_nuance` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_importcursordata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `sourcePHID` varbinary(64) NOT NULL,
  `cursorKey` varchar(32) NOT NULL,
  `cursorType` varchar(32) NOT NULL,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_source` (`sourcePHID`,`cursorKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `requestorPHID` varbinary(64) DEFAULT NULL,
  `sourcePHID` varbinary(64) NOT NULL,
  `status` varchar(32) NOT NULL,
  `data` longtext NOT NULL,
  `mailKey` binary(20) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `queuePHID` varbinary(64) DEFAULT NULL,
  `itemType` varchar(64) NOT NULL,
  `itemKey` varchar(64) DEFAULT NULL,
  `itemContainerKey` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_item` (`sourcePHID`,`itemKey`),
  KEY `key_source` (`sourcePHID`,`status`),
  KEY `key_owner` (`ownerPHID`,`status`),
  KEY `key_requestor` (`requestorPHID`,`status`),
  KEY `key_queue` (`queuePHID`,`status`),
  KEY `key_container` (`sourcePHID`,`itemContainerKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_itemcommand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `itemPHID` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `command` varchar(64) NOT NULL,
  `parameters` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `queuePHID` varbinary(64) DEFAULT NULL,
  `status` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_pending` (`itemPHID`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_itemtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_itemtransaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mailKey` binary(20) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_queuetransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_queuetransaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_source` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `type` varchar(32) NOT NULL,
  `data` longtext NOT NULL,
  `mailKey` binary(20) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `defaultQueuePHID` varbinary(64) NOT NULL,
  `isDisabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_type` (`type`,`dateModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_sourcename_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_sourcetransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_nuance`;

CREATE TABLE `nuance_sourcetransaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_oauth_server` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_oauth_server`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_oauth_server`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_oauth_server`;

CREATE TABLE `oauth_server_oauthclientauthorization` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `userPHID` varbinary(64) NOT NULL,
  `clientPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `scope` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  UNIQUE KEY `userPHID` (`userPHID`,`clientPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_oauth_server`;

CREATE TABLE `oauth_server_oauthserveraccesstoken` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(32) NOT NULL,
  `userPHID` varbinary(64) NOT NULL,
  `clientPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_oauth_server`;

CREATE TABLE `oauth_server_oauthserverauthorizationcode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `clientPHID` varbinary(64) NOT NULL,
  `clientSecret` varchar(32) NOT NULL,
  `userPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `redirectURI` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_oauth_server`;

CREATE TABLE `oauth_server_oauthserverclient` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(32) NOT NULL,
  `redirectURI` varchar(255) NOT NULL,
  `creatorPHID` varbinary(64) NOT NULL,
  `isTrusted` tinyint(1) NOT NULL DEFAULT 0,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isDisabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `creatorPHID` (`creatorPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_oauth_server`;

CREATE TABLE `oauth_server_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_owners` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_owners`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_customfieldnumericindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `indexKey` binary(12) NOT NULL,
  `indexValue` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_join` (`objectPHID`,`indexKey`,`indexValue`),
  KEY `key_find` (`indexKey`,`indexValue`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_customfieldstorage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `fieldIndex` binary(12) NOT NULL,
  `fieldValue` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `objectPHID` (`objectPHID`,`fieldIndex`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_customfieldstringindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `indexKey` binary(12) NOT NULL,
  `indexValue` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_join` (`objectPHID`,`indexKey`,`indexValue`(64)),
  KEY `key_find` (`indexKey`,`indexValue`(64))
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_name_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_owner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `packageID` int(10) unsigned NOT NULL,
  `userPHID` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `packageID` (`packageID`,`userPHID`),
  KEY `userPHID` (`userPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `description` longtext NOT NULL,
  `status` varchar(32) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `autoReview` varchar(32) NOT NULL,
  `dominion` varchar(32) NOT NULL,
  `properties` longtext NOT NULL,
  `auditingState` varchar(32) NOT NULL,
  `authorityMode` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_package_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_package_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_package_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_package_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_packagetransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_owners`;

CREATE TABLE `owners_path` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `packageID` int(10) unsigned NOT NULL,
  `repositoryPHID` varbinary(64) NOT NULL,
  `path` longtext NOT NULL,
  `excluded` tinyint(1) NOT NULL DEFAULT 0,
  `pathIndex` binary(12) NOT NULL,
  `pathDisplay` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_path` (`packageID`,`repositoryPHID`,`pathIndex`),
  KEY `key_repository` (`repositoryPHID`,`pathIndex`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_packages` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_packages`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_packages`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_packages`;

CREATE TABLE `packages_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(64) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `publisherPHID` varbinary(64) NOT NULL,
  `packageKey` varchar(64) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_package` (`publisherPHID`,`packageKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_packages`;

CREATE TABLE `packages_packagename_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_packages`;

CREATE TABLE `packages_packagetransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_packages`;

CREATE TABLE `packages_publisher` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(64) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `publisherKey` varchar(64) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_publisher` (`publisherKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_packages`;

CREATE TABLE `packages_publishername_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_packages`;

CREATE TABLE `packages_publishertransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_packages`;

CREATE TABLE `packages_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(64) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `packagePHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_package` (`packagePHID`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_packages`;

CREATE TABLE `packages_versionname_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_packages`;

CREATE TABLE `packages_versiontransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_passphrase` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_passphrase`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_passphrase`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_passphrase`;

CREATE TABLE `passphrase_credential` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `credentialType` varchar(64) NOT NULL,
  `providesType` varchar(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `description` longtext NOT NULL,
  `username` varchar(255) NOT NULL,
  `secretID` int(10) unsigned DEFAULT NULL,
  `isDestroyed` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isLocked` tinyint(1) NOT NULL,
  `allowConduit` tinyint(1) NOT NULL DEFAULT 0,
  `authorPHID` varbinary(64) NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_secret` (`secretID`),
  KEY `key_type` (`credentialType`),
  KEY `key_provides` (`providesType`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_passphrase`;

CREATE TABLE `passphrase_credential_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_passphrase`;

CREATE TABLE `passphrase_credential_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_passphrase`;

CREATE TABLE `passphrase_credential_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_passphrase`;

CREATE TABLE `passphrase_credential_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_passphrase`;

CREATE TABLE `passphrase_credentialtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_passphrase`;

CREATE TABLE `passphrase_secret` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `secretData` longblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_paste` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_paste`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_paste`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_paste`;

CREATE TABLE `paste` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `filePHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `language` varchar(64) DEFAULT NULL,
  `parentPHID` varbinary(64) DEFAULT NULL,
  `viewPolicy` varbinary(64) DEFAULT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  `status` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `parentPHID` (`parentPHID`),
  KEY `authorPHID` (`authorPHID`),
  KEY `key_dateCreated` (`dateCreated`),
  KEY `key_language` (`language`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_paste`;

CREATE TABLE `paste_paste_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_paste`;

CREATE TABLE `paste_paste_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_paste`;

CREATE TABLE `paste_paste_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_paste`;

CREATE TABLE `paste_paste_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_paste`;

CREATE TABLE `paste_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_paste`;

CREATE TABLE `paste_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `lineNumber` int(10) unsigned DEFAULT NULL,
  `lineLength` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_phame` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_phame`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` longtext NOT NULL,
  `domain` varchar(128) DEFAULT NULL,
  `configData` longtext NOT NULL,
  `creatorPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `mailKey` binary(20) NOT NULL,
  `status` varchar(32) NOT NULL,
  `profileImagePHID` varbinary(64) DEFAULT NULL,
  `headerImagePHID` varbinary(64) DEFAULT NULL,
  `subtitle` varchar(64) NOT NULL,
  `parentDomain` varchar(128) DEFAULT NULL,
  `parentSite` varchar(128) DEFAULT NULL,
  `domainFullURI` varchar(128) DEFAULT NULL,
  `interactPolicy` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_blog_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_blog_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_blog_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_blog_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_blogtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `bloggerPHID` varbinary(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `phameTitle` varchar(64) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `visibility` int(10) unsigned NOT NULL DEFAULT 0,
  `configData` longtext DEFAULT NULL,
  `datePublished` int(10) unsigned NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `blogPHID` varbinary(64) DEFAULT NULL,
  `mailKey` binary(20) NOT NULL,
  `subtitle` varchar(64) NOT NULL,
  `headerImagePHID` varbinary(64) DEFAULT NULL,
  `interactPolicy` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  KEY `bloggerPosts` (`bloggerPHID`,`visibility`,`datePublished`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_post_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_post_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_post_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_post_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_posttransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phame`;

CREATE TABLE `phame_posttransaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_phlux` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_phlux`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phlux`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phlux`;

CREATE TABLE `phlux_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `metadata` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phlux`;

CREATE TABLE `phlux_variable` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `variableKey` varchar(64) NOT NULL,
  `variableValue` longtext NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_key` (`variableKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_pholio` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_pholio`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_pholio`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_pholio`;

CREATE TABLE `pholio_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `filePHID` varbinary(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` longtext NOT NULL,
  `sequence` int(10) unsigned NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isObsolete` tinyint(1) NOT NULL DEFAULT 0,
  `replacesImagePHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `mockPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_mock` (`mockPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_pholio`;

CREATE TABLE `pholio_mock` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` longtext NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `coverPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `status` varchar(12) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `authorPHID` (`authorPHID`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_pholio`;

CREATE TABLE `pholio_mock_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_pholio`;

CREATE TABLE `pholio_mock_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_pholio`;

CREATE TABLE `pholio_mock_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_pholio`;

CREATE TABLE `pholio_mock_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_pholio`;

CREATE TABLE `pholio_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `metadata` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_pholio`;

CREATE TABLE `pholio_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `imageID` int(10) unsigned DEFAULT NULL,
  `x` int(10) unsigned DEFAULT NULL,
  `y` int(10) unsigned DEFAULT NULL,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`),
  UNIQUE KEY `key_draft` (`authorPHID`,`imageID`,`transactionPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_phrequent` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_phrequent`;

CREATE TABLE `phrequent_usertime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) DEFAULT NULL,
  `note` longtext DEFAULT NULL,
  `dateStarted` int(10) unsigned NOT NULL,
  `dateEnded` int(10) unsigned DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_phriction` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_phriction`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phriction`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phriction`;

CREATE TABLE `phriction_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `title` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `slug` varchar(128) NOT NULL,
  `content` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `description` longtext NOT NULL,
  `changeType` int(10) unsigned NOT NULL DEFAULT 0,
  `changeRef` int(10) unsigned DEFAULT NULL,
  `phid` varbinary(64) NOT NULL,
  `documentPHID` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_version` (`documentPHID`,`version`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `authorPHID` (`authorPHID`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phriction`;

CREATE TABLE `phriction_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `slug` varchar(128) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `depth` int(10) unsigned NOT NULL,
  `status` varchar(32) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  `contentPHID` varbinary(64) NOT NULL,
  `editedEpoch` int(10) unsigned NOT NULL,
  `maxVersion` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `depth` (`depth`,`slug`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phriction`;

CREATE TABLE `phriction_document_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phriction`;

CREATE TABLE `phriction_document_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phriction`;

CREATE TABLE `phriction_document_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phriction`;

CREATE TABLE `phriction_document_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phriction`;

CREATE TABLE `phriction_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phriction`;

CREATE TABLE `phriction_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_phurl` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_phurl`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phurl`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phurl`;

CREATE TABLE `phurl_phurlname_ngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_object` (`objectID`),
  KEY `key_ngram` (`ngram`,`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phurl`;

CREATE TABLE `phurl_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` longtext NOT NULL,
  `longURL` longtext NOT NULL,
  `description` longtext NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `alias` varchar(64) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `mailKey` binary(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_instance` (`alias`),
  KEY `key_author` (`authorPHID`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phurl`;

CREATE TABLE `phurl_urltransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_phurl`;

CREATE TABLE `phurl_urltransaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_policy` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_policy`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_policy`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_policy`;

CREATE TABLE `policy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `rules` longtext NOT NULL,
  `defaultAction` varchar(32) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_policy`;

CREATE TABLE `policy_namedpolicy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `effectivePolicy` varbinary(64) NOT NULL,
  `name` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `description` longtext NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `targetObjectType` varchar(8) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_policy`;

CREATE TABLE `policy_namedpolicytransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_policy`;

CREATE TABLE `policy_namedpolicytransaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_ponder` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `ponder_answer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questionID` int(10) unsigned NOT NULL,
  `phid` varbinary(64) NOT NULL,
  `voteCount` int(10) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `content` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `mailKey` binary(20) NOT NULL,
  `status` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  UNIQUE KEY `key_oneanswerperquestion` (`questionID`,`authorPHID`),
  KEY `questionID` (`questionID`),
  KEY `authorPHID` (`authorPHID`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `ponder_answertransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `ponder_answertransaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `ponder_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `status` varchar(32) NOT NULL,
  `content` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `contentSource` longtext DEFAULT NULL,
  `answerCount` int(10) unsigned NOT NULL,
  `mailKey` binary(20) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  `answerWiki` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  KEY `authorPHID` (`authorPHID`),
  KEY `status` (`status`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `ponder_question_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `ponder_question_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `ponder_question_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `ponder_question_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `ponder_questiontransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_ponder`;

CREATE TABLE `ponder_questiontransaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_project` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_project`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `status` varchar(32) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `joinPolicy` varbinary(64) NOT NULL,
  `isMembershipLocked` tinyint(1) NOT NULL DEFAULT 0,
  `profileImagePHID` varbinary(64) DEFAULT NULL,
  `icon` varchar(32) NOT NULL,
  `color` varchar(32) NOT NULL,
  `mailKey` binary(20) NOT NULL,
  `primarySlug` varchar(128) DEFAULT NULL,
  `parentProjectPHID` varbinary(64) DEFAULT NULL,
  `hasWorkboard` tinyint(1) NOT NULL,
  `hasMilestones` tinyint(1) NOT NULL,
  `hasSubprojects` tinyint(1) NOT NULL,
  `milestoneNumber` int(10) unsigned DEFAULT NULL,
  `projectPath` varbinary(64) NOT NULL,
  `projectDepth` int(10) unsigned NOT NULL,
  `projectPathKey` binary(4) NOT NULL,
  `properties` longtext NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  `subtype` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_pathkey` (`projectPathKey`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_primaryslug` (`primarySlug`),
  UNIQUE KEY `key_milestone` (`parentProjectPHID`,`milestoneNumber`),
  KEY `key_icon` (`icon`),
  KEY `key_color` (`color`),
  KEY `key_path` (`projectPath`,`projectDepth`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_column` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(10) unsigned NOT NULL,
  `sequence` int(10) unsigned NOT NULL,
  `projectPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `properties` longtext NOT NULL,
  `proxyPHID` varbinary(64) DEFAULT NULL,
  `triggerPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_proxy` (`projectPHID`,`proxyPHID`),
  KEY `key_status` (`projectPHID`,`status`,`sequence`),
  KEY `key_sequence` (`projectPHID`,`sequence`),
  KEY `key_trigger` (`triggerPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_columnposition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `boardPHID` varbinary(64) NOT NULL,
  `columnPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `sequence` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `boardPHID` (`boardPHID`,`columnPHID`,`objectPHID`),
  KEY `objectPHID` (`objectPHID`,`boardPHID`),
  KEY `boardPHID_2` (`boardPHID`,`columnPHID`,`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_columntransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_customfieldnumericindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `indexKey` binary(12) NOT NULL,
  `indexValue` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_join` (`objectPHID`,`indexKey`,`indexValue`),
  KEY `key_find` (`indexKey`,`indexValue`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_customfieldstorage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `fieldIndex` binary(12) NOT NULL,
  `fieldValue` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `objectPHID` (`objectPHID`,`fieldIndex`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_customfieldstringindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `indexKey` binary(12) NOT NULL,
  `indexValue` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_join` (`objectPHID`,`indexKey`,`indexValue`(64)),
  KEY `key_find` (`indexKey`,`indexValue`(64))
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_datasourcetoken` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `projectID` int(10) unsigned NOT NULL,
  `token` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`,`projectID`),
  KEY `projectID` (`projectID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_project_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_project_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_project_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_project_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_slug` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `projectPHID` varbinary(64) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_slug` (`slug`),
  KEY `key_projectPHID` (`projectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_trigger` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `ruleset` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_triggertransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_project`;

CREATE TABLE `project_triggerusage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `triggerPHID` varbinary(64) NOT NULL,
  `examplePHID` varbinary(64) DEFAULT NULL,
  `columnCount` int(10) unsigned NOT NULL,
  `activeColumnCount` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_trigger` (`triggerPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_repository` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_repository`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `callsign` varchar(32) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} DEFAULT NULL,
  `versionControlSystem` varchar(32) NOT NULL,
  `details` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `uuid` varchar(64) DEFAULT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `pushPolicy` varbinary(64) NOT NULL,
  `credentialPHID` varbinary(64) DEFAULT NULL,
  `almanacServicePHID` varbinary(64) DEFAULT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  `repositorySlug` varchar(64) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} DEFAULT NULL,
  `localPath` varchar(128) DEFAULT NULL,
  `profileImagePHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `callsign` (`callsign`),
  UNIQUE KEY `key_slug` (`repositorySlug`),
  UNIQUE KEY `key_local` (`localPath`),
  KEY `key_vcs` (`versionControlSystem`),
  KEY `key_name` (`name`(128)),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_auditrequest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auditorPHID` varbinary(64) NOT NULL,
  `commitPHID` varbinary(64) NOT NULL,
  `auditStatus` varchar(64) NOT NULL,
  `auditReasons` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_unique` (`commitPHID`,`auditorPHID`),
  KEY `commitPHID` (`commitPHID`),
  KEY `auditorPHID` (`auditorPHID`,`auditStatus`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_branch` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repositoryID` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `lintCommit` varchar(40) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `repositoryID` (`repositoryID`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_commit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repositoryID` int(10) unsigned NOT NULL,
  `phid` varbinary(64) NOT NULL,
  `commitIdentifier` varchar(40) NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `auditStatus` varchar(32) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `importStatus` int(10) unsigned NOT NULL,
  `authorIdentityPHID` varbinary(64) DEFAULT NULL,
  `committerIdentityPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phid` (`phid`),
  UNIQUE KEY `key_commit_identity` (`commitIdentifier`,`repositoryID`),
  KEY `repositoryID_2` (`repositoryID`,`epoch`),
  KEY `authorPHID` (`authorPHID`,`auditStatus`,`epoch`),
  KEY `repositoryID` (`repositoryID`,`importStatus`),
  KEY `key_epoch` (`epoch`),
  KEY `key_author` (`authorPHID`,`epoch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_commit_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_commit_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_commit_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_commit_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_commitdata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commitID` int(10) unsigned NOT NULL,
  `authorName` longtext NOT NULL,
  `commitMessage` longtext NOT NULL,
  `commitDetails` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commitID` (`commitID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_commithint` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repositoryPHID` varbinary(64) NOT NULL,
  `oldCommitIdentifier` varchar(40) NOT NULL,
  `newCommitIdentifier` varchar(40) DEFAULT NULL,
  `hintType` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_old` (`repositoryPHID`,`oldCommitIdentifier`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_coverage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `branchID` int(10) unsigned NOT NULL,
  `commitID` int(10) unsigned NOT NULL,
  `pathID` int(10) unsigned NOT NULL,
  `coverage` longblob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_path` (`branchID`,`pathID`,`commitID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_filesystem` (
  `repositoryID` int(10) unsigned NOT NULL,
  `parentID` int(10) unsigned NOT NULL,
  `svnCommit` int(10) unsigned NOT NULL,
  `pathID` int(10) unsigned NOT NULL,
  `existed` tinyint(1) NOT NULL,
  `fileType` int(10) unsigned NOT NULL,
  PRIMARY KEY (`repositoryID`,`parentID`,`pathID`,`svnCommit`),
  KEY `repositoryID` (`repositoryID`,`svnCommit`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_gitlfsref` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repositoryPHID` varbinary(64) NOT NULL,
  `objectHash` binary(64) NOT NULL,
  `byteSize` bigint(20) unsigned NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `filePHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_hash` (`repositoryPHID`,`objectHash`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_identity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `automaticGuessedUserPHID` varbinary(64) DEFAULT NULL,
  `manuallySetUserPHID` varbinary(64) DEFAULT NULL,
  `currentEffectiveUserPHID` varbinary(64) DEFAULT NULL,
  `identityNameHash` binary(12) NOT NULL,
  `identityNameRaw` longblob NOT NULL,
  `identityNameEncoding` varchar(16) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `emailAddress` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_identity` (`identityNameHash`),
  KEY `key_email` (`emailAddress`(64))
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_identitytransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_lintmessage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `branchID` int(10) unsigned NOT NULL,
  `path` longtext NOT NULL,
  `line` int(10) unsigned NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `code` varchar(32) NOT NULL,
  `severity` varchar(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `branchID` (`branchID`,`path`(64)),
  KEY `branchID_2` (`branchID`,`code`,`path`(64)),
  KEY `key_author` (`authorPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_oldref` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repositoryPHID` varbinary(64) NOT NULL,
  `commitIdentifier` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_repository` (`repositoryPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_parents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `childCommitID` int(10) unsigned NOT NULL,
  `parentCommitID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_child` (`childCommitID`,`parentCommitID`),
  KEY `key_parent` (`parentCommitID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_path` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `path` longtext NOT NULL,
  `pathHash` binary(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pathHash` (`pathHash`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_pathchange` (
  `repositoryID` int(10) unsigned NOT NULL,
  `pathID` int(10) unsigned NOT NULL,
  `commitID` int(10) unsigned NOT NULL,
  `targetPathID` int(10) unsigned DEFAULT NULL,
  `targetCommitID` int(10) unsigned DEFAULT NULL,
  `changeType` int(10) unsigned NOT NULL,
  `fileType` int(10) unsigned NOT NULL,
  `isDirect` tinyint(1) NOT NULL,
  `commitSequence` int(10) unsigned NOT NULL,
  PRIMARY KEY (`commitID`,`pathID`),
  KEY `repositoryID` (`repositoryID`,`pathID`,`commitSequence`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_pullevent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `repositoryPHID` varbinary(64) DEFAULT NULL,
  `epoch` int(10) unsigned NOT NULL,
  `pullerPHID` varbinary(64) DEFAULT NULL,
  `remoteAddress` varbinary(64) DEFAULT NULL,
  `remoteProtocol` varchar(32) DEFAULT NULL,
  `resultType` varchar(32) NOT NULL,
  `resultCode` int(10) unsigned NOT NULL,
  `properties` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_repository` (`repositoryPHID`),
  KEY `key_epoch` (`epoch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_pushevent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `repositoryPHID` varbinary(64) NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  `pusherPHID` varbinary(64) NOT NULL,
  `remoteAddress` varbinary(64) DEFAULT NULL,
  `remoteProtocol` varchar(32) DEFAULT NULL,
  `rejectCode` int(10) unsigned NOT NULL,
  `rejectDetails` varchar(64) DEFAULT NULL,
  `requestIdentifier` binary(12) DEFAULT NULL,
  `writeWait` bigint(20) unsigned DEFAULT NULL,
  `readWait` bigint(20) unsigned DEFAULT NULL,
  `hostWait` bigint(20) unsigned DEFAULT NULL,
  `hookWait` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_repository` (`repositoryPHID`),
  KEY `key_identifier` (`requestIdentifier`),
  KEY `key_reject` (`rejectCode`,`rejectDetails`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_pushlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  `pushEventPHID` varbinary(64) NOT NULL,
  `repositoryPHID` varbinary(64) NOT NULL,
  `pusherPHID` varbinary(64) NOT NULL,
  `refType` varchar(12) NOT NULL,
  `refNameHash` binary(12) DEFAULT NULL,
  `refNameRaw` longblob DEFAULT NULL,
  `refNameEncoding` varchar(16) DEFAULT NULL,
  `refOld` varchar(40) DEFAULT NULL,
  `refNew` varchar(40) NOT NULL,
  `mergeBase` varchar(40) DEFAULT NULL,
  `changeFlags` int(10) unsigned NOT NULL,
  `devicePHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_repository` (`repositoryPHID`),
  KEY `key_ref` (`repositoryPHID`,`refNew`),
  KEY `key_pusher` (`pusherPHID`),
  KEY `key_name` (`repositoryPHID`,`refNameHash`),
  KEY `key_event` (`pushEventPHID`),
  KEY `key_epoch` (`epoch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_refcursor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `repositoryPHID` varbinary(64) NOT NULL,
  `refType` varchar(32) NOT NULL,
  `refNameHash` binary(12) NOT NULL,
  `refNameRaw` longblob NOT NULL,
  `refNameEncoding` varchar(16) DEFAULT NULL,
  `isPermanent` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ref` (`repositoryPHID`,`refType`,`refNameHash`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_refposition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cursorID` int(10) unsigned NOT NULL,
  `commitIdentifier` varchar(40) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_position` (`cursorID`,`commitIdentifier`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_repository_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_repository_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_repository_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_repository_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_statusmessage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repositoryID` int(10) unsigned NOT NULL,
  `statusType` varchar(32) NOT NULL,
  `statusCode` varchar(32) NOT NULL,
  `parameters` longtext NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  `messageCount` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `repositoryID` (`repositoryID`,`statusType`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_summary` (
  `repositoryID` int(10) unsigned NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `lastCommitID` int(10) unsigned NOT NULL,
  `epoch` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`repositoryID`),
  KEY `key_epoch` (`epoch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_symbol` (
  `repositoryPHID` varbinary(64) NOT NULL,
  `symbolContext` varchar(128) NOT NULL,
  `symbolName` varchar(128) NOT NULL,
  `symbolType` varchar(12) NOT NULL,
  `symbolLanguage` varchar(32) NOT NULL,
  `pathID` int(10) unsigned NOT NULL,
  `lineNumber` int(10) unsigned NOT NULL,
  KEY `symbolName` (`symbolName`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_syncevent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `repositoryPHID` varbinary(64) NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  `devicePHID` varbinary(64) NOT NULL,
  `fromDevicePHID` varbinary(64) NOT NULL,
  `deviceVersion` int(10) unsigned DEFAULT NULL,
  `fromDeviceVersion` int(10) unsigned DEFAULT NULL,
  `resultType` varchar(32) NOT NULL,
  `resultCode` int(10) unsigned NOT NULL,
  `syncWait` bigint(20) unsigned NOT NULL,
  `properties` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_repository` (`repositoryPHID`),
  KEY `key_epoch` (`epoch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_uri` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `repositoryPHID` varbinary(64) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `builtinProtocol` varchar(32) DEFAULT NULL,
  `builtinIdentifier` varchar(32) DEFAULT NULL,
  `ioType` varchar(32) NOT NULL,
  `displayType` varchar(32) NOT NULL,
  `isDisabled` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `credentialPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_builtin` (`repositoryPHID`,`builtinProtocol`,`builtinIdentifier`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_uriindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repositoryPHID` varbinary(64) NOT NULL,
  `repositoryURI` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_repository` (`repositoryPHID`),
  KEY `key_uri` (`repositoryURI`(128))
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_uritransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_repository`;

CREATE TABLE `repository_workingcopyversion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repositoryPHID` varbinary(64) NOT NULL,
  `devicePHID` varbinary(64) NOT NULL,
  `repositoryVersion` int(10) unsigned NOT NULL,
  `isWriting` tinyint(1) NOT NULL,
  `writeProperties` longtext DEFAULT NULL,
  `lockOwner` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_workingcopy` (`repositoryPHID`,`devicePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_search` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_search`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `search_document` (
  `phid` varbinary(64) NOT NULL,
  `documentType` varchar(4) NOT NULL,
  `documentTitle` varchar(255) NOT NULL,
  `documentCreated` int(10) unsigned NOT NULL,
  `documentModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`phid`),
  KEY `documentCreated` (`documentCreated`),
  KEY `key_type` (`documentType`,`documentCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `search_documentrelationship` (
  `phid` varbinary(64) NOT NULL,
  `relatedPHID` varbinary(64) NOT NULL,
  `relation` varchar(4) NOT NULL,
  `relatedType` varchar(4) NOT NULL,
  `relatedTime` int(10) unsigned NOT NULL,
  KEY `phid` (`phid`),
  KEY `relatedPHID` (`relatedPHID`,`relation`),
  KEY `relation` (`relation`,`relatedPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `search_editengineconfiguration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `engineKey` varchar(64) NOT NULL,
  `builtinKey` varchar(64) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `properties` longtext NOT NULL,
  `isDisabled` tinyint(1) NOT NULL DEFAULT 0,
  `isDefault` tinyint(1) NOT NULL DEFAULT 0,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isEdit` tinyint(1) NOT NULL,
  `createOrder` int(10) unsigned NOT NULL,
  `editOrder` int(10) unsigned NOT NULL,
  `subtype` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_engine` (`engineKey`,`builtinKey`),
  KEY `key_default` (`engineKey`,`isDefault`,`isDisabled`),
  KEY `key_edit` (`engineKey`,`isEdit`,`isDisabled`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `search_editengineconfigurationtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `search_indexversion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `extensionKey` varchar(64) NOT NULL,
  `version` varchar(128) NOT NULL,
  `indexVersion` binary(12) NOT NULL,
  `indexEpoch` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`,`extensionKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `search_namedquery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userPHID` varbinary(64) NOT NULL,
  `engineClassName` varchar(128) NOT NULL,
  `queryName` varchar(255) NOT NULL,
  `queryKey` varchar(12) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isBuiltin` tinyint(1) NOT NULL DEFAULT 0,
  `isDisabled` tinyint(1) NOT NULL DEFAULT 0,
  `sequence` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_userquery` (`userPHID`,`engineClassName`,`queryKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `search_namedqueryconfig` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `engineClassName` varchar(128) NOT NULL,
  `scopePHID` varbinary(64) NOT NULL,
  `properties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_scope` (`engineClassName`,`scopePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `search_profilepanelconfiguration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `profilePHID` varbinary(64) NOT NULL,
  `menuItemKey` varchar(64) NOT NULL,
  `builtinKey` varchar(64) DEFAULT NULL,
  `menuItemOrder` int(10) unsigned DEFAULT NULL,
  `visibility` varchar(32) NOT NULL,
  `menuItemProperties` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `customPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_profile` (`profilePHID`,`menuItemOrder`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `search_profilepanelconfigurationtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `search_savedquery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `engineClassName` varchar(255) NOT NULL,
  `parameters` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `queryKey` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_queryKey` (`queryKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_search`;

CREATE TABLE `stopwords` (
  `value` varchar(32) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

INSERT INTO `stopwords` VALUES
('the'),
('be'),
('and'),
('of'),
('a'),
('in'),
('to'),
('have'),
('it'),
('I'),
('that'),
('for'),
('you'),
('he'),
('with'),
('on'),
('do'),
('say'),
('this'),
('they'),
('at'),
('but'),
('we'),
('his'),
('from'),
('not'),
('by'),
('or'),
('as'),
('what'),
('go'),
('their'),
('can'),
('who'),
('get'),
('if'),
('would'),
('all'),
('my'),
('will'),
('up'),
('there'),
('so'),
('its'),
('us');

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_slowvote` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_slowvote`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_slowvote`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_slowvote`;

CREATE TABLE `slowvote_choice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pollID` int(10) unsigned NOT NULL,
  `optionID` int(10) unsigned NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pollID` (`pollID`),
  KEY `authorPHID` (`authorPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_slowvote`;

CREATE TABLE `slowvote_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pollID` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pollID` (`pollID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_slowvote`;

CREATE TABLE `slowvote_poll` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `responseVisibility` varchar(32) NOT NULL,
  `shuffle` tinyint(1) NOT NULL DEFAULT 0,
  `method` varchar(32) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `description` longtext NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `status` varchar(32) NOT NULL,
  `spacePHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_space` (`spacePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_slowvote`;

CREATE TABLE `slowvote_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_slowvote`;

CREATE TABLE `slowvote_transaction_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `transactionPHID` varbinary(64) DEFAULT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `isDeleted` tinyint(1) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_version` (`transactionPHID`,`commentVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_spaces` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_spaces`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_spaces`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_spaces`;

CREATE TABLE `spaces_namespace` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `namespaceName` varchar(255) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `isDefaultNamespace` tinyint(1) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `description` longtext NOT NULL,
  `isArchived` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_default` (`isDefaultNamespace`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_spaces`;

CREATE TABLE `spaces_namespacetransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_system` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_system`;

CREATE TABLE `system_actionlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `actorHash` binary(12) NOT NULL,
  `actorIdentity` varchar(255) NOT NULL,
  `action` varchar(32) NOT NULL,
  `score` double NOT NULL,
  `epoch` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_epoch` (`epoch`),
  KEY `key_action` (`actorHash`,`action`,`epoch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_system`;

CREATE TABLE `system_destructionlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectClass` varchar(128) NOT NULL,
  `rootLogID` int(10) unsigned DEFAULT NULL,
  `objectPHID` varbinary(64) DEFAULT NULL,
  `objectMonogram` varchar(64) DEFAULT NULL,
  `epoch` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_epoch` (`epoch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_token` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_token`;

CREATE TABLE `token_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `tokenCount` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_objectPHID` (`objectPHID`),
  KEY `key_count` (`tokenCount`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_token`;

CREATE TABLE `token_given` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `tokenPHID` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_all` (`objectPHID`,`authorPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_token` (`tokenPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_token`;

CREATE TABLE `token_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `flavor` varchar(128) NOT NULL,
  `status` varchar(32) NOT NULL,
  `builtinKey` varchar(32) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `creatorPHID` varbinary(64) NOT NULL,
  `tokenImagePHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_builtin` (`builtinKey`),
  KEY `key_creator` (`creatorPHID`,`dateModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_user` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_user`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `phabricator_session` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userPHID` varbinary(64) NOT NULL,
  `type` varchar(32) NOT NULL,
  `sessionKey` varchar(64) NOT NULL,
  `sessionStart` int(10) unsigned NOT NULL,
  `sessionExpires` int(10) unsigned NOT NULL,
  `highSecurityUntil` int(10) unsigned DEFAULT NULL,
  `isPartial` tinyint(1) NOT NULL DEFAULT 0,
  `signedLegalpadDocuments` tinyint(1) NOT NULL DEFAULT 0,
  `phid` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sessionKey` (`sessionKey`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_identity` (`userPHID`,`type`),
  KEY `key_expires` (`sessionExpires`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `userName` varchar(64) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `realName` varchar(128) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `profileImagePHID` varbinary(64) DEFAULT NULL,
  `conduitCertificate` varchar(255) NOT NULL,
  `isSystemAgent` tinyint(1) NOT NULL DEFAULT 0,
  `isDisabled` tinyint(1) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `isEmailVerified` int(10) unsigned NOT NULL,
  `isApproved` int(10) unsigned NOT NULL,
  `accountSecret` binary(64) NOT NULL,
  `isEnrolledInMultiFactor` tinyint(1) NOT NULL DEFAULT 0,
  `availabilityCache` varchar(255) DEFAULT NULL,
  `availabilityCacheTTL` int(10) unsigned DEFAULT NULL,
  `isMailingList` tinyint(1) NOT NULL,
  `defaultProfileImagePHID` varbinary(64) DEFAULT NULL,
  `defaultProfileImageVersion` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userName` (`userName`),
  UNIQUE KEY `phid` (`phid`),
  KEY `realName` (`realName`),
  KEY `key_approved` (`isApproved`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_authinvite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `authorPHID` varbinary(64) NOT NULL,
  `emailAddress` varchar(128) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `verificationHash` binary(12) NOT NULL,
  `acceptedByPHID` varbinary(64) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `phid` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_address` (`emailAddress`),
  UNIQUE KEY `key_code` (`verificationHash`),
  UNIQUE KEY `key_phid` (`phid`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_cache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userPHID` varbinary(64) NOT NULL,
  `cacheIndex` binary(12) NOT NULL,
  `cacheKey` varchar(255) NOT NULL,
  `cacheData` longtext NOT NULL,
  `cacheType` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_usercache` (`userPHID`,`cacheIndex`),
  KEY `key_cachekey` (`cacheIndex`),
  KEY `key_cachetype` (`cacheType`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_configuredcustomfieldstorage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `fieldIndex` binary(12) NOT NULL,
  `fieldValue` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `objectPHID` (`objectPHID`,`fieldIndex`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_customfieldnumericindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `indexKey` binary(12) NOT NULL,
  `indexValue` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_join` (`objectPHID`,`indexKey`,`indexValue`),
  KEY `key_find` (`indexKey`,`indexValue`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_customfieldstringindex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `indexKey` binary(12) NOT NULL,
  `indexValue` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_join` (`objectPHID`,`indexKey`,`indexValue`(64)),
  KEY `key_find` (`indexKey`,`indexValue`(64))
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_email` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userPHID` varbinary(64) NOT NULL,
  `address` varchar(128) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `isVerified` tinyint(1) NOT NULL DEFAULT 0,
  `isPrimary` tinyint(1) NOT NULL DEFAULT 0,
  `verificationCode` varchar(64) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `phid` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `address` (`address`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `userPHID` (`userPHID`,`isPrimary`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_externalaccount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `userPHID` varbinary(64) DEFAULT NULL,
  `accountType` varchar(16) NOT NULL,
  `accountDomain` varchar(64) NOT NULL,
  `accountSecret` longtext DEFAULT NULL,
  `accountID` varchar(64) NOT NULL,
  `displayName` varchar(255) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `realName` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `emailVerified` tinyint(1) NOT NULL,
  `accountURI` varchar(255) DEFAULT NULL,
  `profileImagePHID` varbinary(64) DEFAULT NULL,
  `properties` longtext NOT NULL,
  `providerConfigPHID` varbinary(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_user` (`userPHID`),
  KEY `key_provider` (`providerConfigPHID`,`userPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_externalaccountidentifier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `externalAccountPHID` varbinary(64) NOT NULL,
  `providerConfigPHID` varbinary(64) NOT NULL,
  `identifierHash` binary(12) NOT NULL,
  `identifierRaw` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_identifier` (`providerConfigPHID`,`identifierHash`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_account` (`externalAccountPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `actorPHID` varbinary(64) DEFAULT NULL,
  `userPHID` varbinary(64) NOT NULL,
  `action` varchar(64) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `details` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `remoteAddr` varchar(64) NOT NULL,
  `session` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `actorPHID` (`actorPHID`,`dateCreated`),
  KEY `userPHID` (`userPHID`,`dateCreated`),
  KEY `action` (`action`,`dateCreated`),
  KEY `dateCreated` (`dateCreated`),
  KEY `remoteAddr` (`remoteAddr`,`dateCreated`),
  KEY `session` (`session`,`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_nametoken` (
  `token` varchar(255) CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  KEY `token` (`token`(128)),
  KEY `userID` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_preferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userPHID` varbinary(64) DEFAULT NULL,
  `preferences` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `phid` varbinary(64) NOT NULL,
  `builtinKey` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_builtin` (`builtinKey`),
  UNIQUE KEY `key_user` (`userPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_preferencestransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userPHID` varbinary(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `blurb` longtext NOT NULL,
  `profileImagePHID` varbinary(64) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `icon` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userPHID` (`userPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_user_fdocument` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectPHID` varbinary(64) NOT NULL,
  `isClosed` tinyint(1) NOT NULL,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `ownerPHID` varbinary(64) DEFAULT NULL,
  `epochCreated` int(10) unsigned NOT NULL,
  `epochModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_object` (`objectPHID`),
  KEY `key_author` (`authorPHID`),
  KEY `key_owner` (`ownerPHID`),
  KEY `key_created` (`epochCreated`),
  KEY `key_modified` (`epochModified`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_user_ffield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `fieldKey` varchar(4) NOT NULL,
  `rawCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `termCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  `normalCorpus` longtext CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT} NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_documentfield` (`documentID`,`fieldKey`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_user_fngrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documentID` int(10) unsigned NOT NULL,
  `ngram` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_ngram` (`ngram`,`documentID`),
  KEY `key_object` (`documentID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_user`;

CREATE TABLE `user_user_fngrams_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngram` char(3) NOT NULL,
  `needsCollection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_ngram` (`ngram`),
  KEY `key_collect` (`needsCollection`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_worker` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_worker`;

CREATE TABLE `edge` (
  `src` varbinary(64) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `dst` varbinary(64) NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `seq` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`src`,`type`,`dst`),
  UNIQUE KEY `key_dst` (`dst`,`type`,`src`),
  KEY `src` (`src`,`type`,`dateCreated`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_worker`;

CREATE TABLE `edgedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_worker`;

CREATE TABLE `lisk_counter` (
  `counterName` varchar(32) NOT NULL,
  `counterValue` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`counterName`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

INSERT INTO `lisk_counter` VALUES
('worker_activetask',8);

USE `{$NAMESPACE}_worker`;

CREATE TABLE `worker_activetask` (
  `id` int(10) unsigned NOT NULL,
  `taskClass` varchar(64) NOT NULL,
  `leaseOwner` varchar(64) DEFAULT NULL,
  `leaseExpires` int(10) unsigned DEFAULT NULL,
  `failureCount` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned NOT NULL,
  `failureTime` int(10) unsigned DEFAULT NULL,
  `priority` int(10) unsigned NOT NULL,
  `objectPHID` varbinary(64) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `containerPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leaseExpires` (`leaseExpires`),
  KEY `key_failuretime` (`failureTime`),
  KEY `taskClass` (`taskClass`),
  KEY `key_owner` (`leaseOwner`,`priority`,`id`),
  KEY `key_object` (`objectPHID`),
  KEY `key_container` (`containerPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

INSERT INTO `worker_activetask` VALUES
(3,'PhabricatorRebuildIndexesWorker',NULL,NULL,0,1,NULL,3500,NULL,1786875828,1786875828,NULL),
(4,'PhabricatorRebuildIndexesWorker',NULL,NULL,0,2,NULL,3500,NULL,1786875828,1786875828,NULL),
(5,'PhabricatorRebuildIndexesWorker',NULL,NULL,0,3,NULL,3500,NULL,1786875828,1786875828,NULL),
(6,'PhabricatorRebuildIndexesWorker',NULL,NULL,0,4,NULL,3500,NULL,1786875828,1786875828,NULL),
(7,'PhabricatorRebuildIndexesWorker',NULL,NULL,0,5,NULL,3500,NULL,1786875828,1786875828,NULL),
(8,'PhabricatorRebuildIndexesWorker',NULL,NULL,0,6,NULL,3500,NULL,1786875828,1786875828,NULL);

USE `{$NAMESPACE}_worker`;

CREATE TABLE `worker_archivetask` (
  `id` int(10) unsigned NOT NULL,
  `taskClass` varchar(64) NOT NULL,
  `leaseOwner` varchar(64) DEFAULT NULL,
  `leaseExpires` int(10) unsigned DEFAULT NULL,
  `failureCount` int(10) unsigned NOT NULL,
  `dataID` int(10) unsigned NOT NULL,
  `result` int(10) unsigned NOT NULL,
  `duration` bigint(20) unsigned NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `priority` int(10) unsigned NOT NULL,
  `objectPHID` varbinary(64) DEFAULT NULL,
  `archivedEpoch` int(10) unsigned DEFAULT NULL,
  `containerPHID` varbinary(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dateCreated` (`dateCreated`),
  KEY `key_modified` (`dateModified`),
  KEY `key_object` (`objectPHID`),
  KEY `key_container` (`containerPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_worker`;

CREATE TABLE `worker_bulkjob` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `jobTypeKey` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL,
  `parameters` longtext NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  `isSilent` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_type` (`jobTypeKey`),
  KEY `key_author` (`authorPHID`),
  KEY `key_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_worker`;

CREATE TABLE `worker_bulkjobtransaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `authorPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `viewPolicy` varbinary(64) NOT NULL,
  `editPolicy` varbinary(64) NOT NULL,
  `commentPHID` varbinary(64) DEFAULT NULL,
  `commentVersion` int(10) unsigned NOT NULL,
  `transactionType` varchar(32) NOT NULL,
  `oldValue` longtext NOT NULL,
  `newValue` longtext NOT NULL,
  `contentSource` longtext NOT NULL,
  `metadata` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_worker`;

CREATE TABLE `worker_bulktask` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bulkJobPHID` varbinary(64) NOT NULL,
  `objectPHID` varbinary(64) NOT NULL,
  `status` varchar(32) NOT NULL,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key_job` (`bulkJobPHID`,`status`),
  KEY `key_object` (`objectPHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_worker`;

CREATE TABLE `worker_taskdata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

INSERT INTO `worker_taskdata` VALUES
(1,'{\"queryClass\":\"PhabricatorDashboardQuery\"}'),
(2,'{\"queryClass\":\"PhabricatorDashboardPanelQuery\"}'),
(3,'{\"queryClass\":\"HeraldRuleQuery\"}'),
(4,'{\"queryClass\":\"HeraldRuleQuery\"}'),
(5,'{\"queryClass\":\"PhabricatorRepositoryQuery\"}'),
(6,'{\"queryClass\":\"PhabricatorRepositoryQuery\"}');

USE `{$NAMESPACE}_worker`;

CREATE TABLE `worker_trigger` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phid` varbinary(64) NOT NULL,
  `triggerVersion` int(10) unsigned NOT NULL,
  `clockClass` varchar(64) NOT NULL,
  `clockProperties` longtext NOT NULL,
  `actionClass` varchar(64) NOT NULL,
  `actionProperties` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_phid` (`phid`),
  UNIQUE KEY `key_trigger` (`triggerVersion`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_worker`;

CREATE TABLE `worker_triggerevent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `triggerID` int(10) unsigned NOT NULL,
  `lastEventEpoch` int(10) unsigned DEFAULT NULL,
  `nextEventEpoch` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_trigger` (`triggerID`),
  KEY `key_next` (`nextEventEpoch`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_xhpast` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_xhpast`;

CREATE TABLE `phpast_parsetree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `input` longtext NOT NULL,
  `error` longtext DEFAULT NULL,
  `tokenStream` longblob NOT NULL,
  `tree` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

USE `{$NAMESPACE}_xhpast`;

CREATE TABLE `xhpast_parsetree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `authorPHID` varbinary(64) DEFAULT NULL,
  `input` longtext NOT NULL,
  `returnCode` int(10) NOT NULL,
  `stdout` longtext NOT NULL,
  `stderr` longtext NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `{$NAMESPACE}_xhprof` /*!40100 DEFAULT CHARACTER SET {$CHARSET} COLLATE {$COLLATE_TEXT} */;

USE `{$NAMESPACE}_xhprof`;

CREATE TABLE `xhprof_sample` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filePHID` varbinary(64) NOT NULL,
  `sampleRate` int(10) unsigned NOT NULL,
  `usTotal` bigint(20) unsigned NOT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `requestPath` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `userPHID` varbinary(64) DEFAULT NULL,
  `dateCreated` int(10) unsigned NOT NULL,
  `dateModified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filePHID` (`filePHID`)
) ENGINE=InnoDB DEFAULT CHARSET={$CHARSET} COLLATE={$COLLATE_TEXT};
