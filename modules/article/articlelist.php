<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

define( "JIEQI_MODULE_NAME", "article" );
if ( !defined( "JIEQI_GLOBAL_INCLUDE" ) )
{
		include_once( "../../global.php" );
}
jieqi_loadlang( "list", JIEQI_MODULE_NAME );
jieqi_getconfigs( "article", "configs" );
jieqi_getconfigs( "article", "sort" );
if ( empty( $_REQUEST['class'] ) || !is_numeric( $_REQUEST['class'] ) && !isset( $jieqiSort['article'][$_REQUEST['class']] ) )
{
		$GLOBALS['_REQUEST']['class'] = 0;
}
$GLOBALS['_REQUEST']['sortid'] = $_REQUEST['class'];
if ( empty( $_REQUEST['type'] ) || !is_numeric( $_REQUEST['type'] ) && !isset( $jieqiSort['article'][$_REQUEST['class']]['types'][$_REQUEST['type']] ) )
{
		$GLOBALS['_REQUEST']['type'] = 0;
}
if ( empty( $_REQUEST['page'] ) || !is_numeric( $_REQUEST['page'] ) )
{
		$GLOBALS['_REQUEST']['page'] = 1;
}
if ( defined( "JIEQI_MAX_PAGES" ) && 0 < JIEQI_MAX_PAGES && is_numeric( $_REQUEST['page'] ) && JIEQI_MAX_PAGES < $_REQUEST['page'] )
{
		$GLOBALS['_REQUEST']['page'] = intval( JIEQI_MAX_PAGES );
}
if ( empty( $_REQUEST['fullflag'] ) )
{
		$GLOBALS['_REQUEST']['fullflag'] = 0;
}
else
{
		$GLOBALS['_REQUEST']['fullflag'] = 1;
}
if ( !empty( $_REQUEST['class'] ) )
{
		$jieqi_pagetitle = $jieqiSort['article'][$_REQUEST['class']]['caption']."-".JIEQI_SITE_NAME;
}
include_once( JIEQI_ROOT_PATH."/header.php" );
$jieqiTset['jieqi_contents_cacheid'] = "f".$_REQUEST['fullflag'];
$jieqiTset['jieqi_contents_cacheid'] .= "_s".$_REQUEST['class'];
$jieqiTset['jieqi_contents_cacheid'] .= "_t".$_REQUEST['type'];
if ( isset( $_REQUEST['initial'] ) && trim( strval( $_REQUEST['initial'] ) ) != "" )
{
		$GLOBALS['_REQUEST']['initial'] = substr( $_REQUEST['initial'], 0, 1 );
		if ( $_REQUEST['initial'] == "~" || $_REQUEST['initial'] == "0" )
		{
				$jieqiTset['jieqi_contents_cacheid'] .= "_i0";
		}
		else
		{
				$jieqiTset['jieqi_contents_cacheid'] .= "_i".$_REQUEST['initial'];
		}
}
$pagecacheid = $jieqiTset['jieqi_contents_cacheid'];
$jieqiTset['jieqi_contents_cacheid'] .= "_p".$_REQUEST['page'];
if ( !empty( $_REQUEST['class'] ) )
{
		$jieqiTpl->assign( "sort", $jieqiSort['article'][$_REQUEST['class']]['caption'] );
}
else
{
		$jieqiTpl->assign( "sort", "" );
}
if ( !empty( $_REQUEST['type'] ) )
{
		$jieqiTpl->assign( "type", $jieqiSort['article'][$_REQUEST['class']]['types'][$_REQUEST['type']] );
}
else
{
		$jieqiTpl->assign( "type", "" );
}
if ( !empty( $_REQUEST['initial'] ) )
{
		$jieqiTpl->assign( "initial", $_REQUEST['initial'] );
}
else
{
		$jieqiTpl->assign( "initial", "" );
}
if ( !empty( $_REQUEST['fullflag'] ) )
{
		$jieqiTpl->assign( "fullflag", 1 );
}
else
{
		$jieqiTpl->assign( "fullflag", 0 );
}
$content_used_cache = false;
$jieqiTset['jieqi_page_template'] = $jieqiModules['article']['path']."/templates/web8_articlelist.html";
if ( JIEQI_USE_CACHE && $_REQUEST['page'] <= $jieqiConfigs['article']['cachenum'] )
{
		jieqi_getcachevars( "article", "articleuplog" );
		if ( !is_array( $jieqiArticleuplog ) )
		{
				$jieqiArticleuplog = array( "articleuptime" => 0, "chapteruptime" => 0 );
		}
		$uptime = $jieqiArticleuplog['chapteruptime'] < $jieqiArticleuplog['articleuptime'] ? $jieqiArticleuplog['articleuptime'] : $jieqiArticleuplog['chapteruptime'];
		$cachedtime = $jieqiTpl->get_cachedtime( $jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_cacheid'] );
		if ( $uptime < $cachedtime && JIEQI_NOW_TIME - $cachedtime < JIEQI_CACHE_LIFETIME )
		{
				$content_used_cache = true;
		}
		if ( !$content_used_cache )
		{
				$jieqiTpl->update_cachedtime( $jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_cacheid'] );
				$jieqiTpl->setcaching( 2 );
		}
		else
		{
				$jieqiTpl->setcaching( 1 );
		}
		$jieqiTpl->setcachetime( 99999999 );
}
else
{
		$jieqiTpl->setcaching( 0 );
}
if ( !$content_used_cache )
{
		$article_static_url = empty( $jieqiConfigs['article']['staticurl'] ) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = empty( $jieqiConfigs['article']['dynamicurl'] ) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		include_once( $jieqiModules['article']['path']."/include/funarticle.php" );
		$jieqiTpl->assign( "article_static_url", $article_static_url );
		$jieqiTpl->assign( "article_dynamic_url", $article_dynamic_url );
		$jieqiTpl->assign( "fakefile", $jieqiConfigs['article']['fakefile'] );
		$jieqiTpl->assign( "fakeinfo", $jieqiConfigs['article']['fakeinfo'] );
		$jieqiTpl->assign( "fakesort", $jieqiConfigs['article']['fakesort'] );
		$jieqiTpl->assign( "fakeinitial", $jieqiConfigs['article']['fakeinitial'] );
		$jieqiTpl->assign( "faketoplist", $jieqiConfigs['article']['faketoplist'] );
		include_once( $jieqiModules['article']['path']."/class/article.php" );
		$article_handler =& jieqiarticlehandler::getinstance( "JieqiArticleHandler" );
		$criteria = new criteriacompo( new criteria( "display", "0", "=" ) );
		$criteria->add( new criteria( "size", "0", ">" ) );
		if ( !empty( $_REQUEST['fullflag'] ) )
		{
				$criteria->add( new criteria( "fullflag", "1", "=" ) );
		}
		if ( !empty( $_REQUEST['initial'] ) )
		{
				$criteria->add( new criteria( "initial", strtoupper( $_REQUEST['initial'] ), "=" ) );
		}
		if ( !empty( $_REQUEST['class'] ) )
		{
				$criteria->add( new criteria( "sortid", $_REQUEST['class'], "=" ) );
		}
		if ( !empty( $_REQUEST['type'] ) )
		{
				$criteria->add( new criteria( "typeid", $_REQUEST['type'], "=" ) );
		}
		$criteria->setsort( "lastupdate" );
		$criteria->setorder( "DESC" );
		$criteria->setlimit( $jieqiConfigs['article']['pagenum'] );
		$criteria->setstart( ( $_REQUEST['page'] - 1 ) * $jieqiConfigs['article']['pagenum'] );
		$article_handler->queryobjects( $criteria );
		$articlerows = array( );
		$k = 0;
		while ( $v = $article_handler->getobject( ) )
		{
				$articlerows[$k] = jieqi_article_vars( $v );
				++$k;
		}
		$jieqiTpl->assign_by_ref( "articlerows", $articlerows );
		$jieqiTpl->assign( "url_initial", $article_dynamic_url."/articlelist.php?initial=" );
		include_once( JIEQI_ROOT_PATH."/lib/html/page.php" );
		if ( JIEQI_USE_CACHE )
		{
				jieqi_getcachevars( "article", "articlelistlog" );
				if ( !is_array( $jieqiArticlelistlog ) )
				{
						$jieqiArticlelistlog = array( );
				}
				if ( !isset( $jieqiArticlelistlog[$pagecacheid] ) && JIEQI_CACHE_LIFETIME < JIEQI_NOW_TIME - $jieqiArticlelistlog[$pagecacheid]['time'] )
				{
						$jieqiArticlelistlog[$pagecacheid] = array(
								"rows" => $article_handler->getcount( $criteria ),
								"time" => JIEQI_NOW_TIME
						);
						jieqi_setcachevars( "articlelistlog", "jieqiArticlelistlog", $jieqiArticlelistlog, "article" );
				}
				$toplistrows = $jieqiArticlelistlog[$pagecacheid]['rows'];
		}
		else
		{
				$toplistrows = $article_handler->getcount( $criteria );
		}
		$jumppage = new jieqipage( $toplistrows, $jieqiConfigs['article']['pagenum'], $_REQUEST['page'] );
		if ( !empty( $_REQUEST['initial'] ) || !empty( $jieqiConfigs['article']['fakeinitial'] ) )
		{
				$jumppage->setlink( jieqi_geturl( "article", "initial", 0, $_REQUEST['initial'] ) );
		}
		else if ( empty( $_REQUEST['fullflag'] ) && !empty( $jieqiConfigs['article']['fakesort'] ) )
		{
				$jumppage->setlink( jieqi_geturl( "article", "articlelist", 0, $_REQUEST['class'] ) );
		}
		$jieqiTpl->assign( "url_jumppage", $jumppage->whole_bar( ) );
}
include_once( JIEQI_ROOT_PATH."/footer.php" );
?>
