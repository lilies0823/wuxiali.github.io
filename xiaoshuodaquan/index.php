<?php
define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'blocks');//�������������û������Ļ�����ע�͵���1.6�汾����ֱ����ģ����������飬�����о���˵��
include_once(JIEQI_ROOT_PATH.'/header.php'); //����ҳͷ����
$jieqiTset['jieqi_page_template']=JIEQI_ROOT_PATH.'/web8/xiaoshuodaquan.html';//���ø�ҳ���ģ���ļ���index_1�����Զ������ƣ�
$jieqiTpl->assign('jieqi_indexpage',1);//������ҳ��־��������ҳ��ע�ͱ���䣬����ģ����������жϣ���ģ������������ֵҲ���������
include_once(JIEQI_ROOT_PATH.'/footer.php');//����ҳβ����
?> 
