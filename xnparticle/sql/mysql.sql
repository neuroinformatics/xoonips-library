#
# Table structure for table `xnparticle_item_detail`
#

CREATE TABLE xnparticle_item_detail (
  article_id int(10) unsigned NOT NULL,
  title text,
  title_kana text,
  title_romaji text,
  edition varchar(255) default NULL,
  publish_place varchar(255) default NULL,
  publisher varchar(255),
  publisher_kana varchar(255) default NULL,
  publisher_romaji varchar(255) default NULL,
  year_f varchar(50),
  year_t varchar(50),
  date_create varchar(50),
  date_update varchar(50),
  date_record varchar(50),
  jtitle text,
  jtitle_translation text,
  jtitle_volume varchar(255),
  jtitle_issue varchar(255),
  jtitle_year varchar(255),
  jtitle_month varchar(255),
  jtitle_spage varchar(255),
  jtitle_epage varchar(255),
  abstract text,
  table_of_contents text,
  type_of_resource text,
  genre varchar(255) default NULL,
  access_condition text,
  link varchar(255) default NULL,
  self_doi text,
  naid text,
  ichushi text,
  textversion text,
  grant_id text,
  date_of_granted text,
  degree_name text,
  grantor text,
  PRIMARY KEY  (article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_sub_title (
  article_child_sub_title_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  sub_title_name text,
  sub_title_kana text,
  sub_title_romaji text,
  sub_title_order int(10) unsigned,
  PRIMARY KEY  (article_child_sub_title_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_author (
  article_child_author_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  author_id   varchar(255) NOT NULL,
  author_name varchar(255) NOT NULL,
  author_kana varchar(255) NOT NULL,
  author_romaji varchar(255) NOT NULL,
  author_affiliation varchar(255) NOT NULL,
  author_affiliation_translation varchar(255) NOT NULL,
  author_role varchar(255) NOT NULL,
  author_link varchar(255) NOT NULL,
  author_order int(10) unsigned,
  PRIMARY KEY  (article_child_author_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_keywords (
  article_child_keywords_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  keywords text,
  keywords_order int(10) unsigned,
  PRIMARY KEY  (article_child_keywords_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_ndc_classifications (
  article_child_ndc_classifications_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  ndc_classifications varchar(255),
  ndc_classifications_order int(10) unsigned,
  PRIMARY KEY  (article_child_ndc_classifications_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_physical_descriptions (
  article_child_physical_descriptions_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  physical_descriptions varchar(1000),
  physical_descriptions_order int(10) unsigned,
  PRIMARY KEY  (article_child_physical_descriptions_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_langs (
  article_child_langs_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  langs varchar(50),
  langs_order int(10) unsigned,
  PRIMARY KEY  (article_child_langs_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_id_issns (
  article_child_id_issns_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  id_issns varchar(50),
  id_issns_order int(10) unsigned,
  PRIMARY KEY  (article_child_id_issns_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_id_isbns (
  article_child_id_isbns_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  id_isbns varchar(50),
  id_isbns_order int(10) unsigned,
  PRIMARY KEY  (article_child_id_isbns_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_id_dois (
  article_child_id_dois_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  id_dois varchar(50),
  id_dois_order int(10) unsigned,
  PRIMARY KEY  (article_child_id_dois_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_id_uris (
  article_child_id_uris_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  id_uris varchar(1000),
  id_uris_order int(10) unsigned,
  PRIMARY KEY  (article_child_id_uris_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_id_locals (
  article_child_id_locals_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  id_locals varchar(255),
  id_locals_order int(10) unsigned,
  PRIMARY KEY  (article_child_id_locals_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

CREATE TABLE xnparticle_item_detail_child_uris (
  article_child_uris_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  article_id int(10) unsigned NOT NULL,
  uris varchar(1000),
  uris_order int(10) unsigned,
  PRIMARY KEY  (article_child_uris_id),
  KEY idx_article_id(article_id)
) ENGINE=InnoDB;

