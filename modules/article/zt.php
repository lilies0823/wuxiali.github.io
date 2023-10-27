<?php
define( "JIEQI_MODULE_NAME", "article" );
if ( !defined( "JIEQI_GLOBAL_INCLUDE" ) )
{
	include_once( "../../global.php" );
}
if ( empty( $_REQUEST['id'] ) )
{
	jieqi_printfail( LANG_ERROR_PARAMETER );
}
jieqi_loadlang( "article", JIEQI_MODULE_NAME );
include_once( $jieqiModules['article']['path']."/class/article.php" );
$article_handler =& jieqiarticlehandler::getinstance( "JieqiArticleHandler" );
$article = $article_handler->get( $_REQUEST['id'] );
if ( !$article )
{
	jieqi_printfail( $jieqiLang['article']['article_not_exists'] );
}
else if ( $article->getvar( "display" ) != 0 && $jieqiUsersStatus != JIEQI_GROUP_ADMIN )
{
	jieqi_printfail( $jieqiLang['article']['article_not_audit'] );
}
else
{
	jieqi_getconfigs( JIEQI_MODULE_NAME, "sort" );
	jieqi_getconfigs( JIEQI_MODULE_NAME, "configs" );
	$jieqi_pagetitle = $article->getvar( "articlename" )."-".$article->getvar( "author" )."-".JIEQI_SITE_NAME;
	include_once( JIEQI_ROOT_PATH."/header.php" );
	$article_static_url = empty( $jieqiConfigs['article']['staticurl'] ) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
	$article_dynamic_url = empty( $jieqiConfigs['article']['dynamicurl'] ) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
	$jieqiTpl->assign( "article_static_url", $article_static_url );
	$jieqiTpl->assign( "article_dynamic_url", $article_dynamic_url );
	$jieqiTpl->assign( "makezip", $jieqiConfigs['article']['makezip'] );
	$jieqiTpl->assign( "makejar", $jieqiConfigs['article']['makejar'] );
	$jieqiTpl->assign( "makeumd", $jieqiConfigs['article']['makeumd'] );
	$jieqiTpl->assign( "maketxtfull", $jieqiConfigs['article']['maketxtfull'] );
	$jieqiTpl->assign( "maketxt", $jieqiConfigs['article']['maketxt'] );
	$jieqiTpl->assign( "articlename", $article->getvar( "articlename" ) );
	$jieqiTpl->assign( "keywords", $article->getvar( "keywords" ) );
	$jieqiTpl->assign( "postdate", date( JIEQI_DATE_FORMAT, $article->getvar( "postdate" ) ) );
	$jieqiTpl->assign( "lastupdate", date( JIEQI_DATE_FORMAT, $article->getvar( "lastupdate" ) ) );
	$jieqiTpl->assign( "authorid", $article->getvar( "authorid" ) );
	$jieqiTpl->assign( "author", $article->getvar( "author" ) );
	$jieqiTpl->assign( "agentid", $article->getvar( "agentid" ) );
	$jieqiTpl->assign( "agent", $article->getvar( "agent" ) );
	$jieqiTpl->assign( "sortid", $article->getvar( "sortid" ) );
	$GLOBALS['_REQUEST']['class'] = $article->getvar( "sortid" );
	$GLOBALS['_REQUEST']['sortid'] = $article->getvar( "sortid" );
	$jieqiTpl->assign( "sort", $jieqiSort['article'][$article->getvar( "sortid" )]['caption'] );
	$preg_from = array( "/((https?|ftp):\\/\\/|www\\.)[a-z0-9\\/\\-_+=.~!%@?#%&;:\$\\©¦]+(\\.gif|\\.jpg|\\.jpeg|\\.png|\\.bmp)/isU" );
	$preg_to = array( "<img src=\"\\0\" border=\"0\">" );
	$jieqiTpl->assign( "intro", preg_replace( $preg_from, $preg_to, $article->getvar( "intro" ) ) );
	$jieqiTpl->assign( "notice", preg_replace( $preg_from, $preg_to, $article->getvar( "notice" ) ) );
	$jieqiTpl->assign( "imgflag", $article->getvar( "imgflag", "n" ) );
	$url_simage = jieqi_geturl( "article", "cover", $article->getvar( "articleid" ), "s", $article->getvar( "imgflag", "n" ) );
	if ( !empty( $url_simage ) )
	{
		$jieqiTpl->assign( "hasimage", 1 );
	}
	else
	{
		$jieqiTpl->assign( "hasimage", 0 );
	}
	$jieqiTpl->assign( "url_simage", $url_simage );
	$jieqiTpl->assign( "url_limage", jieqi_geturl( "article", "cover", $article->getvar( "articleid" ), "l", $article->getvar( "imgflag", "n" ) ) );
	$lastchapter = $article->getvar( "lastchapter" );
	if ( $lastchapter != "" )
	{
		if ( $article->getvar( "lastvolume" ) != "" )
		{
			$lastchapter = $article->getvar( "lastvolume" )." ".$lastchapter;
		}
		$jieqiTpl->assign( "url_lastchapter", jieqi_geturl( "article", "chapter", $article->getvar( "lastchapterid" ), $article->getvar( "articleid" ) ) );
	}
	else
	{
		$jieqiTpl->assign( "url_lastchapter", "" );
	}
	$jieqiTpl->assign( "lastchapter", $lastchapter );
	$jieqiTpl->assign( "size", $article->getvar( "size" ) );
	$jieqiTpl->assign( "size_k", ceil( $article->getvar( "size" ) / 1024 ) );
	$jieqiTpl->assign( "size_c", ceil( $article->getvar( "size" ) / 2 ) );
	$jieqiTpl->assign( "dayvisit", $article->getvar( "dayvisit" ) );
	$jieqiTpl->assign( "weekvisit", $article->getvar( "weekvisit" ) );
	$jieqiTpl->assign( "monthvisit", $article->getvar( "monthvisit" ) );
	$jieqiTpl->assign( "mouthvisit", $article->getvar( "monthvisit" ) );
	$jieqiTpl->assign( "allvisit", $article->getvar( "allvisit" ) );
	$jieqiTpl->assign( "dayvote", $article->getvar( "dayvote" ) );
	$jieqiTpl->assign( "weekvote", $article->getvar( "weekvote" ) );
	$jieqiTpl->assign( "monthvote", $article->getvar( "monthvote" ) );
	$jieqiTpl->assign( "mouthvote", $article->getvar( "monthvote" ) );
	$jieqiTpl->assign( "allvote", $article->getvar( "allvote" ) );
	$jieqiTpl->assign( "goodnum", $article->getvar( "goodnum" ) );
	$jieqiTpl->assign( "badnum", $article->getvar( "badnum" ) );
	$jieqiTpl->assign( "total_votes", $article->getvar( "vipvotetime" ) );
	$shige = $article->getvar( "vipvotenow" );
	if($shige==100){
		$shi=10;
		$ge=0;
	}else{	
	$shi = $shige / 10 % 10;
	$ge = $shige % 10;
	}
	$jieqiTpl->assign( "shi", $shi );
	$jieqiTpl->assign( "ge", $ge );
	$rating_width=$shige/100*300;
	$jieqiTpl->assign( "rating_width", $rating_width );
	$jieqiTpl->assign( "rating2", $shige/100 );
	$jieqiTpl->assign( "voted", $articlescore );
	if ( $article->getvar( "fullflag" ) == 0 )
	{
		$jieqiTpl->assign( "fullflag", $jieqiLang['article']['article_not_full'] );
	}
	else
	{
		$jieqiTpl->assign( "fullflag", $jieqiLang['article']['article_is_full'] );
	}
	$tmpvar = "";
	switch ( $article->getvar( "permission" ) )
	{
	case "3" :
		$tmpvar = $jieqiLang['article']['article_permission_special'];
		break;
	case "2" :
		$tmpvar = $jieqiLang['article']['article_permission_insite'];
		break;
	case "1" :
		$tmpvar = $jieqiLang['article']['article_permission_yes'];
		break;
	case "0" :
		$tmpvar = $jieqiLang['article']['article_permission_no'];
	}
	$jieqiTpl->assign( "permission", $tmpvar );
	$tmpvar = "";
	switch ( $article->getvar( "firstflag" ) )
	{
	case "1" :
		$tmpvar = $jieqiLang['article']['article_site_publish'];
		break;
	case "0" :
		$tmpvar = $jieqiLang['article']['article_other_publish'];
	}
	$jieqiTpl->assign( "firstflag", $tmpvar );
	$jieqiTpl->assign( "url_manage", $article_static_url."/articlemanage.php?id=".$article->getvar( "articleid" ) );
	$tmpstr = sprintf( $jieqiLang['article']['article_report_reason'], jieqi_geturl( "article", "article", $article->getvar( "articleid" ), "info" ) );
	$jieqiTpl->assign( "url_report", JIEQI_URL."/newmessage.php?tosys=1&title=".urlencode( sprintf( $jieqiLang['article']['article_report_title'], $article->getvar( "articlename", "n" ) ) )."&content=".urlencode( $tmpstr ) );
	$setting = unserialize( $article->getvar( "setting", "n" ) );
	$url_collect = $article_static_url."/admin/collect.php?toid=".$article->getvar( "articleid", "n" );
	if ( is_numeric( $setting['fromarticle'] ) )
	{
		$url_collect .= "&fromid=".$setting['fromarticle'];
	}
	if ( is_numeric( $setting['fromsite'] ) )
	{
		$url_collect .= "&siteid=".$setting['fromsite'];
	}
	$jieqiTpl->assign( "url_collect", $url_collect );
	if ( 0 < $jieqiConfigs['article']['eachlinknum'] )
	{
		$eachlinkrows = array( );
		$eachlinkcount = 0;
		$setting = unserialize( $article->getvar( "setting", "n" ) );
		if ( !empty( $setting['eachlink']['ids'] ) )
		{
			foreach ( $setting['eachlink']['ids'] as $k => $v )
			{
				$eachlinkrows[$eachlinkcount]['articleid'] = $v;
				$eachlinkrows[$eachlinkcount]['articlename'] = jieqi_htmlstr( $setting['eachlink']['names'][$k] );
				$eachlinkrows[$eachlinkcount]['articlesubdir'] = jieqi_getsubdir( $v );
				$eachlinkrows[$eachlinkcount]['url_articleinfo'] = jieqi_geturl( "article", "article", $v, "info" );
				++$eachlinkcount;
			}
		}
		$jieqiTpl->assign( "eachlinknum", $jieqiConfigs['article']['eachlinknum'] );
		$jieqiTpl->assign( "eachlinkcount", $eachlinkcount );
		$jieqiTpl->assign_by_ref( "eachlinkrows", $eachlinkrows );
	}
	else
	{
		$jieqiTpl->assign( "eachlinknum", 0 );
		$jieqiTpl->assign( "eachlinkcount", 0 );
	}
	$jieqiTpl->assign( "articleid", $article->getvar( "articleid" ) );
	$jieqiTpl->assign( "lastchapterid", $article->getvar( "lastchapterid" ) );
	if ( 0 < $article->getvar( "chapters", "n" ) )
	{
		$jieqiTpl->assign( "url_read", jieqi_geturl( "article", "article", $article->getvar( "articleid" ), "index" ) );
		if ( $jieqiConfigs['article']['makefull'] == 0 || JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET )
		{
			$jieqiTpl->assign( "url_fullpage", $article_static_url."/reader.php?aid=".$article->getvar( "articleid" ) );
		}
		else
		{
			$jieqiTpl->assign( "url_fullpage", jieqi_uploadurl( $jieqiConfigs['article']['fulldir'], $jieqiConfigs['article']['fullurl'], "article", $article_static_url ).jieqi_getsubdir( $article->getvar( "articleid" ) )."/".$article->getvar( "articleid" ).$jieqiConfigs['article']['htmlfile'] );
		}
	}
	else
	{
		$jieqiTpl->assign( "url_read", "#" );
		$jieqiTpl->assign( "url_fullpage", "#" );
	}
	$jieqiTpl->assign( "url_bookcase", $article_dynamic_url."/addbookcase.php?bid=".$article->getvar( "articleid" ) );
	$jieqiTpl->assign( "url_uservote", $article_dynamic_url."/uservote.php?id=".$article->getvar( "articleid" ) );
	if ( 0 < $article->getvar( "authorid" ) )
	{
		$jieqiTpl->assign( "url_authorpage", $article_dynamic_url."/authorpage.php?id=".$article->getvar( "authorid" ) );
	}
	else
	{
		$jieqiTpl->assign( "url_authorpage", "#" );
	}
	$jieqiTpl->assign( "url_authorarticle", $article_dynamic_url."/authorarticle.php?author=".urlencode( $article->getvar( "author", "n" ) ) );
	if ( 0 < $article->getvar( "chapters", "n" ) )
	{
		if ( $jieqiConfigs['article']['makehtml'] == 0 )
		{
			$jieqiTpl->assign( "url_download", "#" );
		}
		else
		{
			$jieqiTpl->assign( "url_download", jieqi_uploadurl( $jieqiConfigs['article']['zipdir'], $jieqiConfigs['article']['zipurl'], "article", $article_static_url ).jieqi_getsubdir( $article->getvar( "articleid" ) )."/".$article->getvar( "articleid" ).$jieqi_file_postfix['zip'] );
		}
		$jieqiTpl->assign( "url_txtarticle", $article_static_url."/txtarticle.php?id=".$article->getvar( "articleid" ) );
	}
	else
	{
		$jieqiTpl->assign( "url_download", "#" );
		$jieqiTpl->assign( "url_txtarticle", "#" );
	}
	$showvote = 0;
	$jieqiConfigs['article']['articlevote'] = intval( $jieqiConfigs['article']['articlevote'] );
	if ( 0 < $jieqiConfigs['article']['articlevote'] && isset( $setting['avoteid'] ) && 0 < $setting['avoteid'] )
	{
		include_once( $jieqiModules['article']['path']."/class/avote.php" );
		$avote_handler =& jieqiavotehandler::getinstance( "JieqiAvoteHandler" );
		$avote = $avote_handler->get( $setting['avoteid'] );
		if ( is_object( $avote ) )
		{
			$jieqiTpl->assign( "voteid", $avote->getvar( "voteid" ) );
			$jieqiTpl->assign( "votetitle", $avote->getvar( "title" ) );
			$jieqiTpl->assign( "mulselect", $avote->getvar( "mulselect" ) );
			$useitem = $avote->getvar( "useitem", "n" );
			$voteitemrows = array( );
			$i = 1;
			for ( ;	$i <= $useitem;	++$i	)
			{
				$voteitemrows[$i - 1]['id'] = $i;
				$voteitemrows[$i - 1]['item'] = $avote->getvar( "item".$i );
			}
			$jieqiTpl->assign_by_ref( "voteitemrows", $voteitemrows );
			$showvote = 1;
		}
	}
	$jieqiTpl->assign( "showvote", $showvote );
	$articletype = intval( $article->getvar( "articletype" ) );
	if ( 0 < ( $articletype & 1 ) )
	{
		$hasebook = 1;
	}
	else
	{
		$hasebook = 0;
	}
	if ( 0 < ( $articletype & 2 ) )
	{
		$hasobook = 1;
	}
	else
	{
		$hasobook = 0;
	}
	if ( 0 < ( $articletype & 4 ) )
	{
		$hastbook = 1;
	}
	else
	{
		$hastbook = 0;
	}
	if ( $hasobook == 1 )
	{
		include_once( $jieqiModules['obook']['path']."/class/obook.php" );
		$obook_handler =& jieqiobookhandler::getinstance( "JieqiObookHandler" );
		$criteria = new criteriacompo( );
		$criteria->add( new criteria( "articleid", $article->getvar( "articleid" ), "=" ) );
		$obook_handler->queryobjects( $criteria );
		$obook = $obook_handler->getobject( );
		if ( is_object( $obook ) && $obook->getvar( "display" ) == 0 && 0 < $obook->getvar( "size" ) )
		{
			$jieqiTpl->assign( "obook_obookid", $obook->getvar( "obookid" ) );
			$jieqiTpl->assign( "obook_lastvolume", $obook->getvar( "lastvolume" ) );
			$jieqiTpl->assign( "obook_lastvolumeid", $obook->getvar( "lastvolumeid" ) );
			$jieqiTpl->assign( "obook_lastchapter", $obook->getvar( "lastchapter" ) );
			$jieqiTpl->assign( "obook_lastchapterid", $obook->getvar( "lastchapterid" ) );
			$jieqiTpl->assign( "obook_lastupdate", date( JIEQI_DATE_FORMAT, $obook->getvar( "lastupdate" ) ) );
			$jieqiTpl->assign( "obook_size", $obook->getvar( "size" ) );
			$jieqiTpl->assign( "obook_publishid", $obook->getvar( "publishid" ) );
		}
		else
		{
			$hasobook = 0;
		}
	}
	$jieqiTpl->assign( "articletype", $articletype );
	$jieqiTpl->assign( "hasebook", $hasebook );
	$jieqiTpl->assign( "hasobook", $hasobook );
	$jieqiTpl->assign( "hastbook", $hastbook );
	include_once( JIEQI_ROOT_PATH."/include/funpost.php" );
	$jieqiConfigs['article']['reviewtype'] = 2;
	if ( !isset( $jieqiConfigs['article']['reviewtype'] ) && $jieqiConfigs['article']['reviewtype'] == 1 )
	{
		include_once( $jieqiModules['article']['path']."/class/review.php" );
		include_once( JIEQI_ROOT_PATH."/lib/text/textfunction.php" );
		$review_handler =& jieqireviewhandler::getinstance( "JieqiReviewHandler" );
		$criteria = new criteriacompo( new criteria( "articleid", $article->getvar( "articleid" ) ) );
		$criteria->setsort( "topflag DESC, topicid" );
		$criteria->setorder( "DESC" );
		$criteria->setlimit( $jieqiConfigs['article']['reviewnew'] );
		$criteria->setstart( 0 );
		$review_handler->queryobjects( $criteria );
		$reviewrows = array( );
		$k = 0;
		while ( $v = $review_handler->getobject( ) )
		{
			$start = 3;
			if ( $v->getvar( "topflag" ) == 1 )
			{
				$reviewrows[$k]['topflag'] = 1;
				$start += 4;
			}
			else
			{
				$reviewrows[$k]['topflag'] = 0;
			}
			if ( $v->getvar( "goodflag" ) == 1 )
			{
				$reviewrows[$k]['goodflag'] = 1;
				$start += 4;
			}
			else
			{
				$reviewrows[$k]['goodflag'] = 0;
			}
			if ( $jieqiConfigs['article']['reviewenter'] == "0" )
			{
				$reviewrows[$k]['content'] = jieqi_htmlstr( jieqi_limitwidth( str_replace( array( "\r", "\n" ), array( "", " " ), $v->getvar( "reviewtext", "n" ) ), $jieqiConfigs['article']['reviewwidth'], $start ) );
			}
			else
			{
				$reviewrows[$k]['content'] = jieqi_htmlstr( jieqi_limitwidth( $v->getvar( "reviewtext", "n" ), $jieqiConfigs['article']['reviewwidth'], $start ) );
			}
			$reviewrows[$k]['postdate'] = date( JIEQI_DATE_FORMAT." ".JIEQI_TIME_FORMAT, $v->getvar( "postdate" ) );
			$reviewrows[$k]['userid'] = $v->getvar( "userid" );
			$reviewrows[$k]['username'] = $v->getvar( "username" );
			++$k;
		}
		$jieqiTpl->assign_by_ref( "reviewrows", $reviewrows );
		$jieqiTpl->assign( "url_goodreview", $article_dynamic_url."/reviews.php?aid=".$article->getvar( "articleid" )."&type=good" );
		$jieqiTpl->assign( "url_allreview", $article_dynamic_url."/reviews.php?aid=".$article->getvar( "articleid" )."&type=all" );
		$jieqiTpl->assign( "url_review", $article_dynamic_url."/reviews.php?aid=".$article->getvar( "articleid" ) );
	}
	else if ( $jieqiConfigs['article']['reviewtype'] == 2 )
	{
		include_once( $jieqiModules['article']['path']."/class/reviews.php" );
		include_once( JIEQI_ROOT_PATH."/lib/text/textfunction.php" );
		$reviews_handler =& jieqireviewshandler::getinstance( "JieqiReviewsHandler" );
		$criteria = new criteriacompo( new criteria( "ownerid", $article->getvar( "articleid" ) ) );
		$criteria->setsort( "istop DESC, topicid" );
		$criteria->setorder( "DESC" );
		$criteria->setlimit( $jieqiConfigs['article']['reviewnew'] );
		$criteria->setstart( 0 );
		$reviews_handler->queryobjects( $criteria );
		$reviewrows = array( );
		$k = 0;
		while ( $v = $reviews_handler->getobject( ) )
		{
			$reviewrows[$k] = jieqi_topic_vars( $v );
			++$k;
		}
		$jieqiTpl->assign_by_ref( "reviewrows", $reviewrows );
		$jieqiTpl->assign( "url_goodreview", $article_dynamic_url."/reviews.php?aid=".$article->getvar( "articleid" )."&type=good" );
		$jieqiTpl->assign( "url_allreview", $article_dynamic_url."/reviews.php?aid=".$article->getvar( "articleid" )."&type=all" );
		$jieqiTpl->assign( "url_review", $article_dynamic_url."/reviews.php?aid=".$article->getvar( "articleid" ) );
	}
	if ( !empty( $_SESSION['jieqiUserId'] ) )
	{
		$jieqiTpl->assign( "enablepost", 1 );
	}
	else
	{
		$jieqiTpl->assign( "enablepost", 0 );
	}
	if ( !isset( $jieqiConfigs['system'] ) )
	{
		jieqi_getconfigs( "system", "configs" );
	}
	$jieqiTpl->assign( "postcheckcode", $jieqiConfigs['system']['postcheckcode'] );
	$jieqiTpl->setcaching( 0 );
	$jieqiTset['jieqi_page_template']=JIEQI_ROOT_PATH.'/modules/article/templates/zt.html';
	if ( isset( $jieqiConfigs['article']['visitstatnum'], $jieqiConfigs['article']['visitstatnum'] ) )
	{
		include_once( $jieqiModules['article']['path']."/articlevisit.php" );
	}
	include_once( JIEQI_ROOT_PATH."/footer.php" );
}
?>
