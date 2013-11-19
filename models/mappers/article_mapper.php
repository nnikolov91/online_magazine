<?php
require_once 'db_connect.php';
function fixEncoding($str) {
	$junk = array (
			"�" => "'",
			"�" => "\"",
			"�" => "\"" 
	);
	foreach ( $junk as $key => $value ) {
		$str = str_replace ( $key, $value, $str );
	}
	return mb_convert_encoding ( $str, 'UTF-8', 'UTF-8' );
}
class Article {
	private $id;
	private $title;
	private $body;
	private $image_path;
	private $likes_count;
	private $keywords;
	private $date;
	private $status;
	private $type;
	private $writers;
	private $featured;
	public function getId() {
		return $this->id;
	}
	public function getTitle() {
		return $this->title;
	}
	public function getBody() {
		return $this->body;
	}
	public function getImage() {
		return $this->image_path;
	}
	public function getLikesCount() {
		return $this->likes_count;
	}
	public function getKeyWords() {
		return $this->keywords;
	}
	public function getDate() {
		return $this->date;
	}
	public function getStatus() {
		return $this->status;
	}
	public function getWriter() {
		return $this->writers;
	}
	function getType() {
		return $this->type;
	}
	function getFeatured() {
		return $this->featured;
	}
	public function __construct($row) {
		// var_dump($row);
		$class_vars = get_class_vars ( get_class ( $this ) );
		// var_dump($class_vars);
		foreach ( array_keys ( $class_vars ) as $value ) {
			if (array_key_exists ( $value, $row )) {
				$this->$value = fixEncoding ( $row [$value] );
			} else {
				throw new ArticleException ( "No row " . $value . " returned from query or wrong article model." );
			}
		}
	}
}
class Review extends Article {
	private $rating;
	public function getRating() {
		return $this->rating;
	}
}
class ArticleException extends Exception {
}
class ArticleMapper extends DBConnect {
	function __construct() {
		$this->connect ();
	}
	public function fetchAll($table="global_articles_view") {
		$result = $this->selectAllFrom ( $table );
		$output = [ ];
		while ( $row = $result->fetch () ) {
			array_push ( $output, new Article ( $row ) );
		}
		return $output;
	}
	
	function fetchAllSubmitted() {
		return $this->fetchAll("submitted_articles_view");
	}
	
	public function updateArticle($article) {
		;
	}
	public function getArticle($id) {
		$response = $this->selectById ( "global_articles_view", $id );
		try {
			$ar = $response->fetch ();
			return new Article ( $ar );
		} catch ( ArticleException $e ) {
			return null;
		}
	}
	public function submitNewArticle($data) {
		$writers = $data ["writer"];
		$keywords = $data ["keywords"];
		unset ( $data ["writer"] );
		unset ( $data ["keywords"] );
		$sql = $this->_buildInsertQuery ( $data, "articles" );
		echo $sql;
		$this->query ( $sql );
		$article_id = $this->lastInsertId ();
		$this->_submitContentKeywordData ( $article_id, $keywords );
		$this->_submitContentWriterData ( $article_id, $writers );
		return $article_id;
	}
	function _submitContentWriterData($article_id, $writers) {
		foreach ( $writers as $writer ) {
			$sql = $this->_buildInsertQuery ( array (
					"article_id" => $article_id,
					"writer_id" => $writer 
			), "article_writers" );
			$this->query ( $sql );
		}
	}
	function _submitContentKeywordData($article_id, $keywords) {
		foreach ( $keywords as $keyword ) {
			$sql = $this->_buildInsertQuery ( array (
					"article_id" => $article_id,
					"keyword" => $keyword 
			), "article_keywords" );
			$this->query ( $sql );
		}
	}
	public function getUserArticles($user) {
		$result = $this->selectAllFromWhere ( "global_articles_view", "writers like '" . $user->getName () . "'" );
		$output = [ ];
		while ( $row = $result->fetch () ) {
			array_push ( $output, new Article ( $row ) );
		}
		return $output;
	}
	public function likeArticle($id) {
		$sql = $this->_buildInsertQuery ( array (
				"article_id" => $id,
				"user_id" => $_SESSION ['UserId'] 
		), "likes" );
		$this->query ( $sql );
	}
	public function getArticleComments($id) {
	}
	function submitReview($data) {
		var_dump ( $data );
		$rating = $data ["rating"];
		unset ( $data ["rating"] );
		$article_id = $this->submitNewArticle ( $data );
		$this->addRating ( $article_id, $rating );
	}
	function addRating($article_id, $rating) {
		$sql = $this->_buildInsertQuery ( array (
				"article_id" => $article_id,
				"rating" => $rating 
		), "ratings" );
		$this->query ( $sql );
	}
	function submitColumnArticle($data) {
		var_dump ( $data );
		$column = $data ["column_article"];
		unset ( $data ["column_article"] );
		$article_id = $this->submitNewArticle ( $data );
		$this->_addColumnData ( $article_id, $column );
	}
	function _addColumnData($article_id, $column) {
		$sql = $this->_buildInsertQuery ( array (
				"`article_id`" => $article_id,
				"`column`" => $column 
		), "column_article" );
		echo $sql;
		$this->query ( $sql );
	}
	function getColumnOfArticle($article_id) {
		$result = $this->selectAllFrom ( "column_article" );
		$row = $result->fetch ();
		return $row ["column"];
	}
}
class ObjectMap {
	public function __construct($row) {
		// var_dump($row);
		$class_vars = get_class_vars ( get_class ( $this ) );
		// var_dump($class_vars);
		foreach ( array_keys ( $class_vars ) as $value ) {
			if (array_key_exists ( $value, $row )) {
				$this->$value = fixEncoding ( $row [$value] );
			} else {
				throw new ArticleException ( "No row " . $value . " returned from query or wrong article model." );
			}
		}
	}
}

