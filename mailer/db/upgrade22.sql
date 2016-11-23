-- access to applications
select @right_id:=right_id from rights where name='Document Management';;
delete from grants where right_id=@right_id;;
delete from rights where right_id=@right_id;;

select @right_id:=right_id from rights where name='Recruiter Management';;
delete from grants where right_id=@right_id;;
delete from rights where right_id=@right_id;;

select @right_id:=right_id from rights where name='Press Releases';;
delete from grants where right_id=@right_id;;
delete from rights where right_id=@right_id;;

select @right_id:=right_id from rights where name='View documents';;
delete from grants where right_id=@right_id;;
delete from rights where right_id=@right_id;;

select @right_id:=right_id from rights where name='Add/edit documents';;
delete from grants where right_id=@right_id;;
delete from rights where right_id=@right_id;;

select @right_id:=right_id from rights where name='Delete documents';;
delete from grants where right_id=@right_id;;
delete from rights where right_id=@right_id;;

select @right_id:=right_id from rights where name='Add/edit press releases';;
delete from grants where right_id=@right_id;;
delete from rights where right_id=@right_id;;

select @right_id:=right_id from rights where name='Delete press releases';;
delete from grants where right_id=@right_id;;
delete from rights where right_id=@right_id;;

select @right_id:=right_id from rights where name='Make press releases active';;
delete from grants where right_id=@right_id;;
delete from rights where right_id=@right_id;;

select @right_id:=right_id from rights where name='Contact/Membership Management';;
delete from grants where right_id=@right_id;;
delete from rights where right_id=@right_id;;

alter table config
    drop doc_dl_date,
    drop doc_dl_bytes;;
drop table system_emails;;
drop table articles;;
drop table press_config;;
drop table press_categories;;
drop table articles_categories;;
drop table articles_images;;
drop table related_articles;;
drop table job_categories;;
drop table jobs;;
drop table skills;;
drop table jobs_skills;;
drop table resume;;
drop table resume_categories;;
drop table resume_status;;
drop table skillsets;;
drop table resumes_jobs;;
drop table resume_email_templates;;
drop table resume_email_options;;
drop table resume_fields;;
drop table job_fields;;
drop table job_config;;
drop table document_categories;;
drop table documents;;
drop table documents_categories;;

select @page_id:=page_id from pages where name='/users/articles.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/articles.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/jobs.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/jobs.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/jobsearch.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/resume.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/resume.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/resume_register.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/resume_register.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/resume_login.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/lostpassword_resume.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;
select @page_id:=page_id from pages where name='/users/lostpassword_resume.php' limit 1;;
delete from pages where page_id=@page_id;;
delete from pages_properties where page_id=@page_id;;

alter table forms
    add auto_subscribe		tinyint unsigned not null default 1,
    add gw_password		varchar(255) not null after gateway_login;;

alter table navlinks
    add topleft_img		mediumblob not null after footer_align;;

alter table user
    add test_email		tinyint unsigned not null default 0;;

alter table email_stats
    add started			datetime not null;;

alter table email_campaigns
    add allow_profile		tinyint unsigned not null default 1,
    add notify_email		varchar(255) not null,
    add monitor_reads		tinyint unsigned not null default 1,
    add show_in_user_record	tinyint unsigned not null default 1;;

alter table forms
    add header_img		mediumblob not null;;

alter table email_stats
    add control			tinyint unsigned not null,
    add processed_list		mediumtext not null,
    modify list			mediumtext not null,
    modify rejected		mediumtext not null;;

insert into payment_gateways (name) values ('VeriSign Payflow Link');;
insert into payment_gateways (name) values ('VeriSign Payflow Pro');;

alter table navlinks
    add left_img		mediumblob not null after topleft_img,
    add topnav_color		varchar(32) not null default 'light_blue' after left_img,
    add topnav_textcolor	varchar(32) not null default 'black' after left_img,
    add active			tinyint unsigned not null default 1;;

INSERT INTO navlinks (navgroup,form_id,header,footer,topleft_img,left_img)
VALUES (2,0,'ÿØÿà\0JFIF\0\0\0d\0d\0\0ÿì\0Ducky\0\0\0\0\0F\0\0ÿî\0Adobe\0dÀ\0\0\0ÿÛ\0„\0\n				\n\n\n\r\r\n\nÿÀ\0\0a\0ÿÄ\0Ò\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0	\n\0\0!1A\"Qaq2#‘¡±ÁÑBr3R‚ÒCS“4b’²cs£$D5á¢ÂƒÃTt”6ðñ%³d¤ÄÔâÓ„\0\0\0\0!1AQaq\"2ð‘¡±áÁÑBR3“Ôñ’#ÓbÒ”râS‚¢ÂCsDTƒ$4ÿÚ\0\0\0?\0Èc¸Ü\Z`IË“EÝxù+§kjfxðž,ªô{f×ÐÉ}a\r½¸–å[Œƒª50¯‡\n]qs>€=sRŠÿ\0y>úofÞÕ\0æëìíÆ{ å-¬ó÷ÀÛ–ë¹ó(\'¹¯g©çÅÕ)!9í¦û½Ü2ïnä	ÜPè4-<øhr÷B‡œÓÿ\0ê)Õmgfg2˜ÓžÜ£_‡fÐñ#ƒpBh…&æYXÄYä{Éh@ÒxñE ¥Ù\n\nÖI—±ÏÖ5\0Ti^ß.x«YebDòkË„ª—pGfŸ ÓJ5ÎVÓêûÙ|Êi‰Ò$j<äË8 æÉøÊqR€ð¨Ë0ßÌÕõ—ðN(È°cÎYöÙ\r¿ˆqZ	mdO\ZòR2‘€“ ðøqÆ‚H¹ÎS]âg‘\\;Èjk¤å¤TàÞ¦¨1¸RÚD°üÙ(ŽIìÒ~œ@\0B\Zœ+!–ÎåÔn¢‡¼CPxÎxµD§†F5€÷FžUšÒÕ¥<ÁG†—ƒ<2Š9E8²ÛÐ}7ùßNñ—”Ïð¯.Ñvƒ»<µò·Ó‹xËÊSÂ2ºíû’Ôó¥§ã}8íkÊG„e˜í7\Z\nÍ7ùßN;Zò–\nG¯vÍå¶+<É2ÊUpu4íð®3wtl¦¦ÑiœDÝ./¯šßqK‰T^B­ ÖÀ	S¸ý¾\\kÚ ¨nbbÝ¶<?îþúöñm-Üºéj»z-‘íñá›Š\ZÑÃ(½®ÅÜ0¬Ö\"KÐJ›‰	ûvyñ’Jòdjæe¥K¯ÓIO¶ßN)Uå\nséºÕ÷Òý8¯g”š78FÑ.2&W¯Ú8‘Ê0€ó†íŒ XÆž3…\Z‘Å¬!KÚÍðœÒVHñ‹˜¥´¹ÔÖ×ÐÌµ\"±Ê¥X|If%Š†[#õÌ¯ ç¿‡c›eº¹’ãréÛ©v»™‰wkA‰ðºª·ãcwt«¨08Û06ŒBâ„Œ:#tW)ÊV#³¼p‘QÊ>ó—b»~q¾€”¡Ì!m|Õr|çdŒ#Ã0\\’+¨“àÎŸ&+V–£œ0Èàeeë%b´–¬èIZÚ*1ÔYðriLê+ˆ¤šÏK‘Ž¤êÎL¦¤vŒM$V|²Tø~lqk;ÕäÅdÎ×&{Ž>ÇNŸc§O±Ó§/Ã\'J“Tð\'X« Ë­y*±æ\ZéÌùðÒÅX™JNhRªíUÍ…NgP Ø˜:i&)Ì˜I) Jž§P+\0äÒåJ«Líl86£“àê¢¹EÙ3”®ì¡^s¬äR3¨ý88Q\\ \ZgÍ-ÓwD²™ÖÙÓˆã‚é u·9Mæ¼[’w»á5¶Z|ø¾…å\"­ÌÉ¹w$üùåaìë!ml9nþ|£Væg&êõ›n’ÞIm‚¡“YÌÊižxí#”³;jÎu\r•À–ïÚî.9…Ñ§¬ŒŠ\0Pæ‚¹Š‰fR)A-F\rZÊ›†ü#i%[ù—™,«%ºƒC¬$+Ûá»pEeëkzƒ~Ün®¤[k†ŽÅ¡ä»FÔQ@	®³¶\0V‘Ü–9˜¯qw|\'µLº3©v«™sÃÊ«L¢¦\nŸqÜë3j9rùðñÅ‚Šå&tÛ•èÉ¹\\ZncwOƒŽU^PzD w-Æ½ë©‹ƒê½c÷»<8¸Q‰¦RÅG)ÝÔ›­¼âÛÚ¦7 ™9–®n(”qZe$¢¯	-Îï{³Û,Q\\Ìn¥\ZžNcw+Ù™Ä[„Te9Fi/TïKÛËu4Ò½k1‘ûª|†˜\"ííúå«^©.ÕºÞÚmw7üéƒêÔ¨²5®@ñÄ8(D\05D§³îûÜ÷ìd¾¹‘$Vycæ6…øñf·mWÔ®Bx/nçê™®¦+ÿ\01½%ZÜYTfIPÂ\nêK½Õ¯QÄó³Âó9Ÿ{‡RÉP%Ê4šç»#yµôr\\\\O2;Í+È…Û%§w·ÃŒØú‚#–ÃF§skê²žæ{É¥ÈO,U+çÂ×¶Æ˜kw@9ã&ÿ\0©µ1t¸œ±¯uKð×»aLDã¹Œ»u6ç,b(fž%>“sX½<YàÉ·^P?¨®F/\\ïhSgÁŒ¨ŸxoÀQÂ&MÓvoïsiìõôâVÈ®P˜	,W{£¦wsgüãý80´Â³‰¯78ØkŸ?çéÄè¤‚á/÷2Â·sR¿¤§ r– „qÜ)íS?œo§¡yEØ\nÊrßînÚ½®dÓüã}8”**]_ß\"\'öÉ›ýcý8‚‹Ê\0å)¶å¸½ÍúÇúp2£”&‘-Xn‚ÔûLÇýc}8¸QÊàKËu!ˆ¹™S°óéÅÙ2•@\"íþ÷5˜k‹½ÆHã\\Œ)Uòœð\npÀGá”GÝýæ˜\\E¶Ïqy\"ýw’D€gÄfIø±u¶A¯{á-á¨±»uŽû»öé;[ñë#¬CÌ	¯œãe<04=Axc”[Ý/$™D¬tž\Z‰Àww˜€…³lp‚ŒÓ\ZúÆËÆqn5s1°£”žÕå‘¾ñÇœ3i™ÄÁ¸„!ËoÒ¿Â8ÔÒÜÌV£”Õ:ZÂÍÖ9CÉ‚¬~ûM{<Xóî£†rä‘‡Õ6þ†ñQmÂšG:¸RãV”Žwµ§¨áüþ\0\'E[×„ÉùŸë°ÂË	gkHuê®ÀšI6a#O©äõ˜Uåã¬é-ÌSÜåå€úÎ\0PaWHtxÏo47“›‰Õª¾³,+”dc_èŽ.ë\ZFÄªY˜8*A<\\–é\"j9D ½g4±VjÀÜãÔ(ÈÝ„úÌN‰Á¥Ø7!¤éåÿ\0ŸŠ”—\r=Ÿt†(š{†!ˆjgõ•LJ¦2\ZåN¿ë›ÝpÛŠÏð›^¦ÒM­©›{u^Ì»µo‚ÒÝV\ZÝ?ÞÊÁÛ.ÁO(ÛpØeºmŠ1Ÿ§ï//·ë26†©Wg<Ë	^DE G,;³fˆ K¥å\Zê*Téá˜Æ3\Z²¢¢’“mÑÄæ3æÁuÁxmÌ<¿»,0te/¯î8aˆ	,QC)å´hF¹:L;·mvÓi$\\j8….Þ¤jÝš‰G¯¬öÖé÷´¸@„«´jkÅhAÈuŠÜ¨×élgçKKí©­®ìƒ%´¢â%«WDÇú¾\ZcSj®SÂeîÙÇGbhãÜâ2z€4\"¹ù†5\0‰’q ÍÂÙRx\"™3ŠOÚ‡ãÂcBDô\n*—ÓÀW³Õ\r¦uÈ\Z†#T2üR™g€±†QB˜Â‰v©ñ˜evÅ%ŒÌ%…v{[•¢DÑØõ]„[ª==[_[RÎäáh[w?j¸ØC¯l9¡§M>YLkƒFèòq^Šüó‡\\4D¡â§O˜pø±AŒ¹Âx†CãÇRueÈM)™ÀšaX% \0(>Q_Ÿ0Ž#BPJ(´ó€0‡¬\"½à	4¦b˜„U òâ²óÒG†žLtéõCvc§N\\ÀjcÙP8yq\"M%u,ƒºkø<Åúq%j>Rô¤œM«„L„€GÄØ @8Èž,ÃTDŒóÐ>RÇNŸ|¸a\'ŽRH]áèÐ|ŠtÊ™6+:}Ž>ÇNŸc§H¦ Z·£õ¼˜²çð\\Ñ©œªÊù,­õW³áÃ\0ÅpRURJ¤Öç»§‹4‘VƒîªûR\ZË1“Ä8Ÿ6\n _)B_g\nIõ–G2|‚.p&Rš)5*3VYmÏà¡ÁÁƒ#	îðÎ‘¸¡€Æìwê<ú1cr!”.$Xà2[ÇYZß”‘ø6,’86I\Z{”Ž^ü2„[Šz´¢~\r=ù5Íå¨’‘Ê([ˆ€IRµû8„Ig½]^]\\H h’•åHåà>!†’Ö“X»]vÃ„4@“-(‘äÑþV±lFº‚„ý~f`~Œr”\'\Zé313i‰EZOÂ=ƒYºw_XN‹P£øheD¨Â*ªÕF¯8ypDˆ—ö«Ka2KqÞyZ–©ø!%¼Ô¦Í¨Ò[”VQ}½3°î£—ÕáUá‰lzd… q%½Öë=Õûú“©ÂššéË³ÒTá™Êp5R}ÎXcÚ	µbŠé——Ýå®yœð+dƒÚ— ‡	íÅ¥Çîm¡©”\"¾à8€NºIÂ•„:_¥ïeÛn÷‹™.V–ÐºUßOòpµÍÒj*a–ÓP”_Ùdf¹½º‘*Õ£/à“™8Ð¸s0 d Ôßžiå´K_h“[ˆÊ6žê“Jù°=%€íB°¦4š¿Dî·)Ó¶ÆæÑÈ#JÖ‚§*ã2ý“QXT¸i=œ;M#RšiåÇi¦uWR¥\\­‚.SŒ¯(’I·‚¸2ÁJ—\"Šxð@%‘±@Á{H¦,Z’eømb¨àqð‹1ÆxlÑš¯Š†©•ÕIô°Â¢Ÿ_ëy1s$13‡– šG1ÒBšÁq‰˜^\ZŽ8FÍ%[ÄÐ‰­ñaRÀ!\'¯»m{Le÷•‰©©\"Í¼ˆ;Þ~rÍ¦l\"·Hà&}Ô~ñn%–X¶‹NZ×ö‹Š;©’ú?(éÄôÂZJŒLÏ®Mîå!¸¾¸yXœ‹µiä˜ªmËˆÙp8J76É\0îæ{:õ‘lK+VT $j5¦Yó†\"‚¤ø¼1„„Bïè©>.,–2Ú©=Ì€(qÚ´\'R²~|¿‡Ù†<SÏ„‘?Gt¥œ–‘DŽì%–²5nPœÀ¦|)ëã­kõMKj¼[\'‹šÒz©¦ê!èááÂ…\\Oqê+x¢53ð9{T\'ý,G„d ÅŽ¥µyÿ\0¼qÿ\0x„ÿ\0¥†Ñ†)ò‡6îÙ†\\ñåšþ–\0ÖŒ¯ˆŽnðè­fý|?•…|#-âˆRÇtŒ‘Þ˜¯‡·ñ°\'¶aRà‡â‰ª9Ë¶E\'á˜SA‡ñw¿êž1,Nööõa:!«¶´-\\l+\0×À2Œ·‘]!’­%Íã÷žžª5û-LP¨¤¸	¬¡w¬’Hºä€Äñq§ð›L\\IeÔ0ƒ£KÓ³ÖÆßÉlQí™d¸$=KpÛ¤i°Èõ*ePU}ìÍº î¶ªPJÖl[,Mq»I3ÍOFI–‚£Ñ\\ñ,n6)@Ö”U¥sÔ<Ñ¤VsF‹k2©o0Õƒ¥›”Ç(»n¸elwñ<‰Eœr:Õ~:å€Ü´a-ÞšÇK]ò£›Jê©»9øë=»NÔô»G¬û¨7h­mã–„±,M)Ùç›I‘¸¾Èwÿ\0y)hh‹—‰Ôÿ\0µÆý¹0_~ñ`ëòò`Ñ£ŒþvILbž^nÛ;Pctí‘¬jÙú¢KTžëtà\0Ó\"Ü\r#ÊYˆÂM`6	L!íÞ({u‡ÞÅ–ö5f\Z$ROœ\ZaS°»Æ‘£æ«€1S©úûi¿¸X¤žFŠ 9pÇ\ZÍ,š³ Ïm¶®œ¢w÷Kw*Òdû¦ÿ\0o°o¯ Û=–›ÑÌ#/¦ã i«°ÓˆVî$Ðý2åƒZ ¡#è’^uu•º¬q+½Ê7y’5\0åc[;gÌËŒ,&½Ñ]\\»¦×\Z•}qÐœ”dÙx|#ûÝ®‡ëŽm75ZÞøÚÖøÓŒ’†k-Á-ÅsÝo€}8SK±ÏFñpøp\"¦0„¡‘4ƒCŸ“\0aKÌ Ò‡<ˆ@Ò^jø\"’Ú¦uïzfÚìºw¬¡BW`Ý¢p`@+·î”´ñÎ“5»Æ§—T³ZûãÞ2úf_˜¯enÐûŽD)quº&X¬¨Rž\nø|xåB02ÁÄq•Mâ/€}8&ƒ¬I“qˆ<ÃéÅ\r³..	~ßq‰²ï|æ8[1”¸!»I@† ð‚>S…„u0³®@W\n•†Õ%ŒV’Ú§-pª3¯Ë‰Ó;T/ÃRP%EÇŽd§.åÙu\r6ÍN9$gçÄŒ³÷˜A>j†îÀÂ€@ˆñ5¨Ì{äÏuŽ7f™ÔLŸ_6*Ná9F2!wmUncŠfT$æµó‹…9P{V-p¤¿XèË¬!¥Wáâ¡?Ms§ºCB¤û:}Ž>ñã§J²H­YÑŒååÅ×”«e)O2	^Þ\\ª5³x+ÃäÁÀå¸i\\\\[šë«IÝ»Ç°8¨`K2yWRÌ—mÞXwtF5Â&Ì$‘˜žr² «¾œTšAH6ïr³¶åêÔÆHù‘IÐƒ¶µËÉ4‚7@5÷P—L‘¹‰X’%G*šñÃ©·17Ü‰F~ VþÔOj¯!  «êíã‹~™ P$+ºÁ/6KxÂ?n4ŸÃ‚xFSÆYn¶‹¦pŽÖrhHÞƒ&ÕO	áÞ0ƒçÝ-£nC	56fjƒŽ,˜}k(M¼[´ŒÍŠ-Ø€´_YÝ¾–¶ZfúÖŸ´QÌIÏ\nÇEÉ?‹-“YÍp÷©lr#¬žËä\\³û^2¶	•¾êHÌAÒ]rP.Kèö}l46æ@Ö=GhÌè«(xõôWº¿‹¥’+T×.mGbâø«˜âLzŠšÏð¼Xç°Jƒ^2ž\\8™Cfê+¾œ:¾¹ªôWÈÕÄ=8šp„ÐÃ){iÚbÝ/¸mÑ¤w§t\0~µ[Ü\\[i˜’ŠIƒ:—w³·ÜkhgöD@Z5xÓ½‹ÚÛ³\nˆE•/:ò.¢VÓGmEˆÆá{ÍÃð±/lý¡%,03U=G[[ÚJ¬« (yzÇ¥õ±ŽÖ[\\*Ü\Zfy>ý·Gy}lŠÐ›‹™U‹ÇQþ6YN•˜5¶MODF”[~á9ŠGy#ª´ªŠWÂ~¶Øš÷d€ÅiÎký9»ØEÓ»v´—_$UPjXŸÂÆ}ë9•ÕL!ê-¹ª’T×ê¯‡í`>–Õ#‡t¶žBá%Òrà¿•‹dJ=Ð²Žé»ÚA* YETžáûX·†Lµ—Ô	ƒ}²ôš9^œ2\\¿ÎÄx&s÷hÏÌhe\n¹ð_ÊÅ—nÍ! Ý_·¬zR)JþV\n6Æ*lTÖwgÔwQ´šd\0}Z/åbÿ\0§\"\nùÒ@‘_ïvI*’sG¥@¼?‰Me­5L\Zz†Â†±Ë£·%ãülÙ1½&BEjQÊÁ//È¿•-.V*ï¾ðö-½¹,d¹¹OÍBëå}Tl\nò0Àš·!ÊÒjÄ\n	œnÞñ·òÉkªÊP¢ |,OÉ„Íûc\ZOe}»t·h³sWâÍCñ“\\oín)¶Bý¦-A(îÅ¡“JoÂ\0\n|¸ýê)¦&Õ†¦2Þ*)¢\0ð¨N%w8`¦\\Ùæew»IeåI¨W<€úp¼ô™qoH¨–\"k%ŒHí }8bÝ”\rµRx·vÂY*¦™§¡Œ@¶åj æÂµôâ­º¶râË-îãy…b;8}8ó÷o«ª:öú6ðö}8¿‰Ðe4úçg‰ÞecÖ´ýTaRÕ÷ÄœPú£œ°:û3òä4ÂÎ6âvã†GÕºr‚wž`CXäáþåÁÒ\r}1ŠF7yÏ«~?î±âôô¤j¸|ãfÇ™ú©+O÷hÎx0qŽ‘Ç\'è¥Ïÿ\0–‹	KC\\SjÊ…ð{6!åÒ‘Dpêñ~ŠŸŒE-ÖáÃµ9”®ZmãaðœðÍ¬ n@òÞK}y…©§öeíû9aà•Š¡‘E0}3™½WrÉD/é«>£.™RkÃo	^dÂú(Ó%í>Zâ,²Nw%”žn°jÞ¡‡G¦K>€fw¸ÜîwÓKpŠ;¢j·ZR¿ÂÆÈ˜‹Pÿ\0Á³Z‡*Í@Ì ìðŒð­Á„ëb†h;&ˆù`EEŒ›Â¢“JÑ¡öö·µö{h¨ÅI\'•Œ¶´\nÍTº@Â%oÇrÜ¥d„ÒJ\Z·+‡\ZV«S3p·.‹uT7{ô\"Xc‘î_DwrGÌ$ü=ß¶uúæpQB+—¹ÔWÖ–œëåºÜ÷.P+2ÐG?gUsWÄ‘.´4¥e7êÂ}¶-¶$Ñ\"*ÊÊé\0>3Ç[Þ9œe´ãÏ”æÚiB€¼Í4ý%è2Ê\r†8ÉäÜ. ½´¸·ˆ‹‹P\\JUÖµ<\r8â¨µ5‘§²Ep1w¬\Zçt¸µÂ¯´I“¬+˜ Â»­¸Ò#›F¥V°¤H.RÁ¤¸ÖV¦ªk¥:PBàÒHäfŸîÂ~MÚÚ³€é<iFËåÂ{õªVWlÔ¹×6Ûh²><Ãè”B0ÅC€1Œ¨–×\"2ÀáD%mšW¶q”–‘FG„â$Àý[Ó±õwJï}+#ˆÎóc=œS0¨Žiò¤§ð$ÒãÉ‚Ú¸m¸z‘CÃ:q÷J\\·â!^ù’ôgZÍ{³t€¿ˆ,›”sZ_MR¼ÂÑ¨ÊÓë2Ê8å§’þ×‡ŠÐÓ ýXO9kp(ƒ¨¯V^Øþê¡ðc, D‘cŒJŠâµ2À	=‚Ç–’™ð¥Ê™{`áˆ/ƒ0Eá…ZÜqnB)2åSŸƒ\0+\Z{5äQ€]†F¿øãŠè\')m`J¾Û\r	Y€Zð©4¯›ˆá;Ugbæ6	Io\0V`F;Aå\'œí@hg¯HÜŠÿ\0§˜÷Â\nÀ×=M´Æ(I¸Ÿ´ˆ,øL[äÃlHÇ_Ê*÷ÀÈÔÎ#Þ÷²K¸’1Â%GÆÇ6‘rS\0.±ãØî×!¹^Ý‘-r¯ˆàMeN:»nyÂ6{ÅÌ²òÖáMJ&ŸrÐºa5¶ª&“x»I¬îÃJü´ÅVÐ#(#x×9›åábVM+Ø4©ù±aiyJ›ÍÎX‡wŸÙÄ³K•xÑ~`1Clr„\r3”æß®Ø÷$Ò½Õ­<ãE1-y¸VMòé§•9Gºº•x6,¶…`Úñ¤£.ëp´çG«w¥Ÿxc:EÅò8Ê¯º¼ªÆiœ¨¶DC+Ãù¸!x1:¤_¼e2Ë\Z‚ûÂ¯¬iUÐ|xí\0õAxŠÀ—[Ë¬Ñ˜¡×:‹)pÑë ƒ—‹ŽM¸\"g¾à«VPšé-m$’)b¸šþ¦j€yJ{iÛL[Åaþ6m\nÅ¬ÍÉk+™Êì`ºŽ<¹œ@øpÕq8:áççYSp“=¼þ‘On	)E|ÐH Œé–ßüI?H;FáJkðç\r¼[³›è†›\'%V×ðHÊ¸ïJxÆ´•ÚæÒ†Þ5çÃpYnDÌjWÍ‚xIgÂ±wt»…`gxàˆ‚qÂG\0wpÊ(Â1‹7R—z8Ü¤£ÀŸùa¤\\zaAÃü1r•Ô™Àom¤êí4Ã\Z}¨k‡¼œÒÌÊš\\¦µÐ<|ˆÐÉÈh¡·¤a;æzWW‹JÐr3&3m›dÒtuæãÉÛtƒFn‘š’>(×Il\"÷)â\0r\")Þ&çÁ#+Y-“ÄWÇ†™®ªá³Lè¹æ—mži¥w¸¹`3á¢Ë	î49˜«©®ºþ\'mõe<e·‰¾#†¶CújzGÆ>¸¿Ó¶\\u„mëš²2ý‹0Ùë„w:Øî\n\'êKÜÆaˆŠÌ;«àˆõï³\r1Ç3ƒéúUþ·fµ×Âj£Ša(:‰Õ¸° ŸXm…‡øe©e³…mökÏ?“\\f\\$D«)K (Jxq@•Œ\ní’6”ÄbâÙ‰n\"æûx¾ÚÀñ\0ÓáÅÑ3šeªAÖW~¸–4˜*&0·“	4·ðÉX‘ªÄçÙƒ\"ã-‘Œ¬LŒt×º0Pºq…Öß˜µ@ðŽ8Y¦mçÆE¼r,í½¢I0F|ÂG”àªà¹œ¡,6£Hƒºu}²4VUº“0ÎAH‡ˆHà„PvŽ<yMUPs\\P¼ß·õ1Ï+£UŽ1å3Š-\0 “NCõ˜M`	a{‹ƒ÷_ˆòc.å£qôœa[JÖzväå“Zé¹Ø.šò‘âã9Í`Ô§.\Zqe·Q\'Œh³«\"jÆœ1†ÃMÊÖ3˜…5!•“ê¦‡ÏÃ¿ª@¢¤û\"Ú­È»iu¬\rŸ\rD—gpuÕEzá‚áJÉÖ\rÉŸ(ÔÌÕ×æ80Ý\\-ˆÙR‚’h¶K©É•Ê|.~ƒ‡Ú¿iˆöÁ–Ó„í:}–¤¼dŸ)ù@ÁSmk\ZQŒï™Í¶Ì¨Îp	­(•ú06uaÙ—$ša.~ë‹ôÞ/C³áÃ?©^^ïœ_úóbéx¹È}¢&îœ¹sx~Ö<«Ý£H\0uPMBÃ£àX ¸-J•*Ü¹+OáS³Í¸8GŒ±‹=mÑë,ÍqF\ZU«R#WÙÜ\ne»dƒX•mÐòÉ8¤‰RE=EÎyý¬3úœ=>âÃ ¥´XÄº¿™—òð«ï(Sµ\'‰‡#é‡\\~ª_ÊÂÞ8–ý9†,:l¡õý\\¿•=àa–Á—çØ«PËÂŸw/å`vï.öIŠ›—J<ŽHp+üÔ§ý,87‘S·5‚åé	)N`áú+]È¤]öÆ¿)PtŒ ýàÏù™/ýHåï”ý)çî–­úZå*c˜)ðreÏüìU¯ƒ»r?„3a°úÑÍ\n\\}c£#Çë`/tŠ…K8ö ×ÝúÁt×Ö2Â,µjž1º«ül7¬p1kû@\Z¢³»nŸÄVÕa”\'‡ÖÁNäã\0»R\rkîŒ{FÅ\"ÎæE ùZ˜RåÑ·dÆôÛÀ…”H¤@\ZƒËŒöº	-’\"¦ï¶è˜Èó·Ž¢\"~|:—Jõ£1ŸxKn—«É0¥r%=ñålmíŠ ¤ÊkÈ¤+EÍ®¼òÿ\0ê$ü¬høâGêÿ\0ÃïùB6Ý0#¾Q/åb\ZúÊÉ<==Í·HJwÐËùX_ÇYVºÇ„íºRä#,r2–ªÅ\"üdŸ“ú §ç)RÜ%=Ë¢7+›9bwz©j‰}ÒºÓé…¶Å\Zºe}¤7`öÆiÚË©-¾î^=½Œ><)cr\0¡—ÜGPXéÓ3{i¸ÆüéV@˜›&>°ÁoîHµ•,àP‰¼Ym²4Èd$²‚{†µ¦}¸òowìRÕ@2ô[k¬‹þ\\Ý‡[Ro`~\ZñËŠxž·kjÊ\n‹³B*ËinÜ*~´(YÙ¶jq?#Tïge!5Œ±Ú¤é˜%ßE-ŸRufÃíÒY[ZîÐõ†×9ŒŽ+íSMeG/˜.SÄ¾Lz¾æ–‘€® PJâs÷O-¸µý[Š{!hàðËà2õM6Îº³†}Z‹ ï¨9qœŽ2\\éb&­°AIc#dŒ‰þ\\@q8Û2Žï¸mÝ7ií»Í×%î¢T-,”ìE?“³mï5WáÜ_·¶]W\ZŸÕ3[ï|[ŒwmûlOc!¼ŠGøÏ.E©åW¶Æ½Ây«Ÿý„†ì(§Ní¿¾¾£SYö{W_\nGqÇÌlsy-ž}ßT…ÿ\0ìw8¢ûåë/|[¤·*×{=«Ú“I5% ø»\nþ.þOlËšú¡íÿ\0ö\'´‹O\\p°÷‡Ó÷Ä$»eå£­é¥OŒq™sË®¦LÜ³ç6.a¥–}ón…9Ñ,“Tw#%I8LYsÂkþ­\0¨©‹;¦á¾ïlaY’ÎÌþf(d«ØÌ^§ÍL;m-ÚTõª$÷î\\Ã!Õ:±éù³™j\0È[Ÿ±K—Å2÷Ë[¶c-®Ìê ff¥	$åŒæ½Œm-Âqí÷\"C Fvµæ8¡!LÄýÑ¤–möÉCóurËö`ÔxsÏ×†RºL’âÐ%°\0ÒF¥j„±øð5|eY0ƒÞÖLÎ£äÓþ\\4Y·˜¨Rç@àºNUóâj$i2\'¶‘}¥8ÕO‡¨JD®ðL¤W-¯!D9ÓÇ,i*û#F!q#BSÊÔ\\¸âÕèÒdíòI%Ä²òô¸¶ÈƒŸÇ<=;¹À½ºç*Ë·ê)’„¿/W¹e,JÚW·W>¨&´)„\Z6Ù¹’\\ƒm:d„±4í¸mnCð¯”­>Æò<¦	]¤œj$\0;{qqtA½ƒè\'“l± “CLÛ™\0Œ×ØA¯‡.Ž‰OÓ·?ts°Îc­Ì³Ì\\\'±É oV Öž–KÀp÷ÅnmÏ?t\ryÓ’ò¹Aýlžå9Ÿça´¾=]­ÎGÀð×rŽQ\rältµ=îíon;ÇÈ{eü\"WŒs²]­«Gj÷³{‚R¢2s*¦¼——íz±‚d`j á\\tÜÌ´i.åÚ“îA€ƒ¯ÆkáÃi{ï|`‹Ó4ý#|îÁ™ÌòPÅ ˆ*<z°OWç$_ Ë:û¥\'—Jë*UË@Úœ/­†ø¸N]ÆœiXûµ	Ów;Ä²Éí’ðGÊa¢%5á«:ã3õ•»‡Æ>Ú½dañ‹’ô­ÜÒ©·–@E?³‘Þo¯5Zù¡&#úÐš{æ¤=¶Â,HíHoR}\"„·ÖðœdÐkš½YÆÞÓP7EfMuÑS[ÚºÂÒ3“ä7ãca/¨JtE­îÉlDÒ6†¼Ûö½58¤jçÔšë’­ø_ÂÆê5>™¨PÜ¹Ä~ºé»æß×+â‰F˜øx\rX×ÙÝÈë‹3ønV’‡»î‘½¿ë·™ÜkyXs\"dâ\0ðã··ô\n“\\)\n.kPffÏqÓ÷7\r©‘é^®ùXÆKâŸ9V²kòŸŸ·ž™ÜõÕñI\nÍq+-!nÒ|xõ6ï\nçkrÓ” é[û«´„£YÓSeZ9h•Ç„»n€Jý3u~—÷\\+¾µú“Ø ~0ìó€@ÝêAmÑWŠ¿%2üË~V	ú‘èaË52—\"é+äUïI¤\nç	úqeÝC3o±(wó\\M,Ò<æ¯Ü·Óƒþ­Gñ®é•@¤îþã˜¢&®˜cþ–+ú ŒfÎìžË_ww‚@IÔ7ÅÞÅ†áGñ„}ËS»&Üº<m°´—÷œµåVüÈ</j4P	ë™ç~€åC·~¡ÜQM®Ïo$q®\\ù¡:š‡ˆE4ÕK*ÃµAÐq†Qlö›ããÚ÷mêâI7f¸p22!!|‚´ø0e¶«Z¶¡ÂZöå,ŽÈEºtÛÛØ1ÒÞ—è›éÅn[VM\"’›}î·ùÀçn¢\0$jÇ\"ƒ£âÁ“YÏÛÈc“QY6M«…‡Â6®i å]ÈÂ5GUcø<	u“Lè—ªˆ~Ó¤ofµö…‚f@ ¶˜€ò‘‡ŽÕp©™·7À5*=°í³=²÷QÁ&žƒ|øÌó\r‚éªÍ+€ÙÃ[NÇu.ÙnBÉJvFÇ­xCÂ¦^š¿zŽcä‰±oÒWŒ¡¾µoÑ»¤§JÃ6dj¬M‚ÚÚ…ãûÄ\\L«ºXÜíž¦Vh4ñ.…käÏ\\ˆÔmÑûQnîîEîÄeš¿XÆQ~3\\fÜ·w™oTl2ôO£·¾š*÷’½‰¯ÂsÆµWCÐ \Zð}û¦çÃ\'‡Ð<p_öõûÇ¹)úÑ?xlAŒâ®ü÷”ñvcçws3­}ž©¨A!X’ŽõÑúuÆcâfºà\"çPÜ³2¦· RhL1mb÷ZRÙ]Î·v¤|=zq8»È·]½_¦ß­\\-Ó¨WSzmúÕÄ´¢Â±+\nz_­L-\r>™dÒ}:J˜•œÐ=ÂË©½/ãƒòa‘”¥*ËO­úÊ|X²Á6r‹	}ÖŸ”3¡®™êýhù±S\"\\‚9YÕ\\¬c¾Áë§VC>ÌPšK(¬æòÞS`öÐ*°®¹e’~r>Y\Z¦RêUb¼t&BI©s=A§ƒ¸­&J\\¦›m’ª¾Ÿëp£¤Ó´ÐÄ—\Z\"úùÒà!õDÎ ¾¢8ï×?Îáû+2·O2ëû­Nþ—üÅ1²«0‹A±ÍŸÖÿ\0˜ÁdéHRÞ|€ÓØ3æÔàEgVŽã!Ýþ³+.\Z_¶š´%­À™a•¡˜\'F]\ZS<‰rÌsø°1…l)!°ˆC¹i™õBhÍ~<-ZÀZŠC°BÑº¸P\nžÀAË­D’3LÙ\'çY…$ZG7Ö=NÝª°˜­p´fu\\øâ%§JåO$2È®+IjË\ná†x¡€Ï‰§,t‰uä¶]E°o²\"˜÷Ÿ§ï‹z/ªn`V´)*·‹mÓˆ£Vè˜b¡nÛ¸iF­¶¯N {k9è¶™vãµÎ®‡ow·˜(Z(˜¢°ð‚\0¡Á7 kÔ>Ö0~[¨[ÐkØ$cÈd}‘­Òh­¥–Ö!=Ò©0ÄÆŠÏØ	ðc8HÐMrRTTÏÏ½Me¿\\u¿õEÒO»>fÊîÀ”ª†®H gL{M«[¿¦(¼ùýsæanó_þ¹Ï\0{¿P”b{»…“géÛ!<Y{EÄqë‘ÈáV>ŠÔd0s¥H{†\"Ë­Áµej8šb}|3eÒwï$Vû½À‚ƒ›<eAU^Å¨¦¦óÐaWÞ( ¯	¡kÊîáÓÄý]	~öÇ£¡·0ÂnV¡e\0Ô°ðø°o¸&§(Ýë[%Z\nÖ/mFNg¯Ånº¦ß†n)™ûwV>Y\\ÑQ#j¢øxc&âóž¢ÅÌ€Ê4ív“ÜŽb)=»<g—\\,Ù²…£µŒP­]ØÈxPTíp“4Uç/Ã7z¤©þ\r?ÒÀY¡D²\ZÆžvÐž?k¡0 Ïž^èÐFœÉË<üuÇ(Í*Hç·‰í8($ÊÏ%<ß/IJÊ²\\Q²ôØR˜ X\"Ò„×ô$ÔP-\rOi<)æÁ–Ü7Y·4óA‰!RƒøA›˜VWmÍ’=Q35÷x’sÁ°s‚.}R£nwYžÔ!Ï‡Å‚økÂYõHd¾1Fq]UæjÔxp®.¶àè+*.ñq# 2ÉA^5Ô1‘ÔW%kËÒ¥byýËs‡…1]\\3œ¢ÛƒA	}L¶Ê™>’ÎìÁÕ»p¤wKÉÝ‰n\\.Bx°âÙ™í¹¹(Ë}8¬¬ëoO‹÷gsnwþcs\"Û•\ZM+‰6’]¸e{ÙîÑ\Z0Ò¹	\ry8\'Å‚Ç2Ä\Z2-Æ	öó—N’ˆüu¥E8bësÅ=PW¬ÂéKI97rAÔÄ”ì\rÇ\0Ý\\ÄöÊÑÅ¢Ô×ÕZª¢\0-‡ÔÑi24kr:c¾ñ¦Ï<@é	lÈ|tLeÛ5»Yèo¶4ò™îÖ†]ÆÎH¼ªŠ™üØÛ~äòiø‚i·Òrì/&fÈDæƒŽhßN0Ó¿=Keê™t6×77ÐÛ½Uô™—ðk\\m³Q+1BÕ©5\'Ûa%Ç5B¢ªðÓL`V¯Yé­öT	õ	[æiíãì¨¨Kp%<8ÙÚÛk®y­õÆ„_é[¹W\\Kå‰åðÈÓî×M‘+³Ôfç5Æ¸S`^Ñ®V&×öþ®<â\'jn]~ÄÇ·[K—˜E\"h1×/)Ç¨V¤ó–V³í§l’;øõV¬È<ÕuÁ¶aoA67+ÉH—*LyŠc6‘é+H\Z™­@ÁiÍQIð¹\ZÓJw“Ú£VB‘s3,hÂ¾—‡cA^2¬}‘{uê]lfInkˆøÃÖ|ç‚ùðõ­µ×\0ð1kÛ„\0Pcw.¿¸¼¼Hvò¶¶Õï£ËÃÃÀy³Æ½¿-Q‹Lë·o2çßîÒI6fcœ¬Y‰«\rÛ¢áÂ#fÁ&¬`&éË‡\Zäz+žé=•Ã>8Êh\\Ý„õO,¶—ŽêHjí$Ž–zÀ]½â\0FrÕçLÉ=*/,RO\0<\'ýN“¶fk¸Àû–Ë.Ï¡fu¨oWÞá†-ßK“KC˜½g´Úî³Mu9Ô5Ð¤\nvœ\'~é\rEšv»\"†M‰½½…¿zr¨ÞRÛ€Ðî™¬A~¶[[¬,’GE‰Rš”,N-á³\\©Ê³Ì>•¹×1ÝöÒG•Ø’ìÌkàÏ<5}@ZOA·hÁ±O\r¦Ë\\H±….5\0Èü8Ë¸¤ŸT`Ò°µQÙE	x‘Ås¦•øNxfÍ“Nˆå©ÊV¹ê{©Q©:Â¹1ŽöÂ8ul¦b(èÕÊg{Ü4ÆVvrÄ÷˜’pÊ€&¶ÜPA|)„j·¶›Œe¢‘ÚU:=Qqª\\ö1úFá\\1áÁø“ö.Ã¹XC|¿ù3òéÇÊ.Ûjœ&š0]5ŽÏ¿Z-¾LùýÔþNÐÕŽËÎ+ßïVO1%ß.?Ùäá”¶Ü Zè&\\Ù7{g,;šŸ÷Sù8Ûm\\¡‘ÀŒŸ½l¤:u?w/Ùÿ\0É€øm	­a+;ûF9ýGù0&C%]aX¯í«–¿Õ7Ñ€i0šÄæçv¶EÒÚòþi¾ŒrÚ$Ë¢’Þ-\0f«þ©¾ŒÁ<¥|e¤¡&ÿ\0¶©&I<”ÿ\0F,¶Z¯,¤wû	A(ÎÊ§ôGç7€Ü ¼eç)Ë¿Ú@#’‘q SÄF.¶\Z™J5õ\'O8WmêÈíìf²…Á‚àÖ¯lãŽY\ZPá{›ljxC®ãO`¨:§o‚!·G,«(#[-»Pƒ\\³zÅ‹Ú¦;uy@Ò3‹öÛ¥‘e£·mG³çòaÏ‡	š6m[•¡ÑF~<y|ƒÝ¶ÕÊiÛ¸´Î¹Ü­&G ROªcÙäÀE¶¬)º´Îg=C¾mÁ^³?o÷ÿ\0Ž5vöÚ™L«ÇVS5½Þö÷z™\ZŸðÿ\0äÆš©™âÛz\\ï»l\'ö„#´4,>DÅ©ŒŸ		^N±³V*³Â¢ž®A•<Kˆ*%¼–íz³o,µºŽŸbo£e–ð[”7Yí±¡9×À2G%<ï£(L¸¶Â°êËI]C\\HÇÇ›km%U„=&÷·FÑÝ{AS‘b#¼§ÉŒëªÂhÚ1 oÛlá%ŽQ¦e)\0ýa^ÅðâUZuÂ+ºO}´y›V¢P®–âxva=Ý–¥i4vw—*Æ÷¹ˆg«â8Ì\nf¡a9×N¬ûÑ‹è25‰Ù»‰F©\"qÔõQðšbº`g\\u‡LZY÷[pÉé*70â†gy²Smí•Í„¨Þñzl {I¤»VàÑ£øX`£Ë¯q‚>ef’L¨Þñmä%`‰cð4…Øü\0yq˜÷ rcª=âìà&ß½É±–Žîš22\'ª2å]JGfÛys÷“;wæ)Ý|r0§Fn–ó¸&ç»E²²24Ó)Š¦•Rªà1©§e0\rí´´šI«Bì.Ý»sVš%8ÍCª˜Q\nÇ¦1+=\r\'æî§‚Î®Ó·²n½A}6›Û†ÔÅ¦c©’ d¨*\\×äÇ¶ÛÞ>‹piP0ý|\'Í7›ãøVIvcÚcÄòõqùMÓ¤ºKjé­,­ÛÚ.Ïzòíõ’ž$/`<¦ëvû‡©ÀpÝy—ÛÚ[Ò¸ž\'™ƒ}àõ¶ÙÑ¶6ísÝ^_jKhdbFlä)ËÀ1åû\'Ü±¡ \\à|Ó~6¨LÙ¹ùþç¬¦ê=Ù-`<ËÉXG\r•¤/é1à¨\'>ÒqìÊÙR9fIŸ;ºÛË‚Fy4½ŸÝWSÍO¸˜ì™³1HÁ¤P\rÆï5³Z.3Ðí|‡qJ½ã6î¶°Š8g”iLßë;2Æ»¼.j\'ª±°[`{\\0*ÃÑà\0?0ÂœLÒÀ`%ˆ}¨]éàã_“jð—Zw›-5€B„Ÿ›¡2ø	Èš&¯{?xµ­dROÕ—ƒ<ñp¦Q˜J3^B£Óãâ9|X(S\\A·•¢WTÁÁ¨xü:Û1vº ¹÷‹:•i‚Ìþ‹Ñ¸|:ÙckËÎW±Ü¬®wKXÖ`cI¢y™À¦c·¹lªž©îã®Þz¼Ùïw;i†	àŠfˆÀð-4Jjµ§mp[MVÃTƒNq‹»­.VƒÊ.õ´ö[F÷&Ùlì(ŽCÕÝY@pÃ»@×-ê3?zVÝÝ\"ƒƒjèØw;!2^ÉwˆHh#ÕNòžÑ…ÚÁ{åO.qÄ¾ÀaÏ”VÜ:ÞmÂÌÃu-¸™YyP,n®UU·\Zöa\rE}³&þõ®.“OdŸ¥ìcÞ¯£·wxaÒÒ™èUQUš¤ÌtÆÒÖ’6VÅ×¥„³¿{É·Ù\ZKM†?bXÙUn9bIæZzNÌ¤çà­Ÿ./ÎÑ÷k¾fÒÎÞe=³Þ¾ñK~°Š+û)H^x„%Ì5È2:¨­<Ž/scáãhé<«€_0ñ\r/\0ËÎ˜Ž¨«{¹m~Úa³”°Ši`i\\‰\0\Z\ZcU-µ1$°®CÝi-µ§Gt½òXŒÒ*]Ì´ÐWáÂ@Þ=Åé	«»¶\r‹Dh~0¿C\\ÚÚtuïT[F²îKp-VršÚŒaƒ¨`i©ŽšÓïCËi»´¯Y¬cbXk£½Zu\nE‡ë±ÕW¶Vè\"šúÌ¿¼DE\'h‚ýÛ\0\\óÐ]‡‚-iL¸uÄrÛ‚U=î=R}×Þ›%è´³šß–W›¦kD‹&t$\n½ˆººÍ}F×w®¥)NÆ=îý]&Ûa²Í·¯v›kù—Ù££I:’ÌŒªGcXÚøŽÀ×#9¹tm¢åŠƒ”Ë:y´Þzÿ\0o’à«Ëw|²´b=1’æ§»¤(<1½½¶Sl@à>‰çv ¾è1´Ð·Ž³Ñ¹Üm·ÖV—{`¸0Ëm-º†hÄº(¬ Tà|8É³³ªRU©ZÖlßÝÑô2†ZÒ”™¾íg²ô¿¼ÆØâ˜´Ó9T£1D :)4Î©ë.×öºÎdO9{h¶wD.A£Ý„»]ØaxGîÛU;†àhG¨‚®TÔ}sDòœd\\ª\0Ìà:Ï¥fÍ†W$èÄõ¯)ŸûÄö+.¤‹t´m;^ñ{\nÄ¦ô“!Å2Ó³;gµ¡»ÉÙ>¯”Çó;:\\2ŽËv‡¯ê1Ïoë¦Û¶þ\\/	‚$2zËu‘«@xºŒÆòÿ\0É5öÍ;>`RØžÉ[Þg¼[¾™Ý\"µÛÖÊ5–ÆÞè«ÙC!/4z‰©Œñ8Ÿ-Ø-ä«jïÞ?\\Ÿ1ÞÜ´à(ZQ~Êñ™·¹kÈwz{T³*H.å¸æ©NãkŠFôH¥*8cwÎ”®ÌÓìñ_.·*HÏê0®ÿ\0ÕVv»„›M„Á®\ZViYRŽñÒ¼0z¨fåÜ)f*§\nÆýÂï§ýÝ[û=Äv÷½rÑ¤÷ou:+.rêX£Œ&@¤cŒëbîðÔ¶k…\r5tõG.ø~\\€\rtŒIÉz:à]»ß.÷u»Cc¸_ÅhåuÙ\\ÚÄÐ{\0®ŸÅ#Üò[A	U*y†5øÄ“ÎwDÔaÈHõ½®Ís³§TlÃ“fA}i›r&\"ªV¹ènÏüSÆµ¹á>\'0y®mîQ^Ø»o‘Õô>å·î}U·ÙHDÉ3H©ÒA‰ê\rFuÃûl–Y†}pZ5^Ž½P¼&±Ø6ØºcÕ}Óû€cg8Éc‘}(e\"¤2øøŒ9åÅ®±·s¾¹õso1·§KZÅ#Èò13Ý¯UÝuïº[^:›xöMÖa^àd¶r¤TfGq£æ;u²ŠGßL}p»»Ç²ß	„n×«³jeŽC@ÚHZù)egAÂ±dòÊèQÕÄ×¯p±B„…WV«Æ™`[‹”\ZWŒÏóLT\01Ÿ¥úw¨ãØýÕØî¶l..w‰í^ií£œ²ˆCéõˆÝ£qcÅÞ”$Ó@88ôM½°}¶ÉJ]g0ˆ½gïrÞ T¾È-–BÐÙÅ‡ZN8ÔÒ‡†7¶>Z–˜²×U8±?LÃÞnî_RJW‚¨øB>ïíömÚÇtÝ··kn—Ø![âXÁ×!éŠê=9XPx0˜\\{eQn9 úIèï,òÔu-s¸¸Ÿ zà=Çÿ\0pÛ½…ï²ôzZôÞÏ+¥¼m!JåÍšDgvðš&µä6®­n“q¹’}À`&³ïžØ¥ z\0÷˜geëÞ˜÷¥<}=ï\nX÷ïQ·u-´osoq!¢sÂI#\'#Q—ÆÜlnl‰`š.hMAA—±¸ýObðÄäÃ0zi˜˜Uôå×JõmþÃ¹Ò+ë	ÚÞá¥X©È©¦a†`ø1­ox—m‡S\ZË#i<\'è{Ž«½è¯w^î›cµ±q´¼’ñî,`¸yW4Z´ˆ[§y[VSq¹»¬œ¥	&­ËjÊi#ÀJ^ñ—lÞ}Þí=wo·Ûm{ÅÔûnã¬f+yž4æ¤©ÉM*\ZœN5üµÞÖåì, ÄŽ¬ÀÞØK¶–ö­R\r2=3?÷\'°ìýQïÎÛÑ.ÏW“nÇp[Çk+5kÙ†¼çrÉa´×dºˆç—Z\ZÅrÆ¾È¯ï¡ èÎ¤Üú~î­%ŒÍ\Z=\r#ÞÆ\\\n°òãCa}/XWGñ‹îRå»…yñÿ\0·?w{OVub_ï\0ƒh1½Æ±Ý{›‡äÚÅÞÒT#0§wÎ÷Þ²¨{Mðâ}“[cc[U²‚c;Õ¼;MõÕ”Í¦X$h¤\06L„©ââ1éöWÕ’µ™—m°jEë¨Z^ër\nOÍŠîo.Ã[CI\0‘\rªì‘…ƒ©ÆI„Æÿ\0h‘¬h\rTq öy°áóaH€ý3Y/ýE‡ÇÀý/û‚s”ý)Ÿ¬º~(Cþgý><\rÞñ„cºhV*z\'¯ÀFr±fí¡2ÓÔçý6\\¥Ä¿´$:‡Üñ¦ðà-$Æø´iüÇõ˜´+eøŸÖ`/,ahB\"ÔòóÏó˜^\Z¿š\Zç£úÌ Þ\nžâ¤z¿ë0ÀJ˜\"Ø@—og4a%H]«©œÊe†\0 €¬ê9!„æµH\Zè2¦Xƒ&X¤\'ÙmÎ ê>Ÿ‹:C¾Ü=ªòúÐÀ\'c‰ß]A\'=><YV¤J1 ¤N–Ý¥{™Yã•ãXÌ²72º›³,i3ãHˆ\\+,ØÛé™¨\"5:h9µÈW·,Uš¢Pˆãµˆ•\0%v/	\\Ê5lñ’î³¢BÃ¹èŸÃðb¶ç;ÌŸ¨§B_Ðíü<kÙ\\\" Å‰™h ýRŒ@§ã`Ü$Áû‹”cF=¿œáœ¸~k‡F¦öJÍ¹¡Œ\0\'²_˜cÕ¬ÔÐwœ·òp»¼\"¤öÎü;Uÿ\0?çÀÑÆ2ì‘³c®Q×ñþœTµ ôÍ\0FÓÙQF¡ÕPº…88BéÀÃ\"â#/O!—oU5×nå3Qè?y~:àVnaI{öñ¬c²¾DÂg©OK»Æ«å ÃlºÅ\"jÚ\reÝßÞÕÜa½‚ÎÞØÎLÍ;ÓÈ4¯ËÚòÄûLOº÷š?ÙP=ñOÿ\0ÙC»ÜKl›³DÊšÙ-‘ î§Š.®4íÆ‚l¶ëôã3Ÿ}¸lØÑ„»[%?x_ó&R™eiå\'£Vl8–þèú\"Osï¦sasðf±ÛÉ&xË¡Œ8=Þí|Ž8³©·Fl«!\\ª®t„ú{gÝv™ÜîÜ‹KXŽ«•¸™cª!ØÒ\nyƒÃJvà‹ÉpvjO\nN¨}µ‹–Ïn€q©öýqk¨=äûºéû›…Üº²+èÒFäÃ`påk’÷BF2þ;…P*šsúªcCnÌÆ„°¯õÐOzsß6éÔ—Pžè·™l¡h£êâåF€ŽêË(XÐž>“°º­»¤†-¤ã…T}%½Q–k–”¨aý}sFÚú‡¯`ºŽó«ú¦;iRQ\ZìöpÇ:†uÔ­0còÆZ´iÏÛ{inÕpïTûŽ8õÒrnw«rí1î€=ã:«¬}ãmä¨Ü&m£ºE[q( êx•Œ‹BE@æÔÎXÉ.o³\\éÎy|&½¿2OµL«Ê½Yüe.Ø:nß7=ãd²žÓ¨îœEto]\Zá9ê$\"4wÈ0\"¥<˜¶êæá ¯FXaŒ®ÒÞÝ\\”7Nxã„Ð-V1¨w™“6‘Ñ•sÏ‰Ë·LØZA{ÖÅÒýc¶Äw¸`Üvµõ°ÊÒR08)yð{W¯mØ„ª·WÑ½bÎáAz2õý2^Ÿé•épßôÞÓm`ó\n<ð.©XÎa%ˆóâ—·7n÷ØŸ‡²ÆÖÕŸÃP:xûdûÆõ²lPó÷«øl£<9®Ÿ\"\n±ø1[Vn]4E&Zõëv…]€‹\rï¦nC6Ø—ÛžŽ/gi#¯ñˆ·^í+ÖÑ÷+\'»©º”ÊþÂÚX“>×ºA§ëIfäyé‰ýŒ™O®pß§aê“ZûÁé+™9Bå¢qY`hÈòš\r¶7†?L\"ïí6Œ›þÏz¿Ø¯qØ¹|XPØuÌF—p‘—¹Æ•5qÅ).ZWžJTœ´ú>3‚ˆ1~îñ¹†%]2\Z™	ì>E™÷_„wuq4„fL`šÞ8–ë¸æ”9E-ÛxQ®Ò:r¤Ì@;|XÐµc&ˆ\\½LR^¸y÷½¾9¦b#ºŠ™äH‘qmÚÑ\ZœŒ.ÌÖâž‘5\rÆm’âÿ\0{¸Ûöx.º£l¤x\'–VÂ¦*¦­$©â”áðcÏÛ[P3Œ8åóžŠá¶YÊ .§qéùL~½¼Þw—½¼*×·/ªb	â(\0§`!OeÝ½+”óî5ÇÔÙ™ \rÍ6w¶²Kðû„‰Ê»ŒÉ\ZúªÔeÏ,bx^.äŠ•ìðë›\"÷…µ»\\z¢.éÕ©¼Ù{4;”1J¯=Å„\rºV Š—l|Ö·´6ÍK±ë8L‹û¡pSJ¯PÇãú¼ö{ß ;	¶»´²ÔÕv¦’È¾21Ÿ¾ (NZ„oËëGî39Ü\"/2™¨™ÈÍÀãvØÔ)1ãX×o»Ä³´y›wçÎóv¥KŠj¦§­+ÃÚ÷Eˆ\Z(?âš¡6ºju×þÌ§×Û&ßÒWé¶í2;HŽ5É*©Yb	PDÓ,[ay·\0W8\rþÙ,]¢šÒë`}Ýô¹‘EH%wOF¡¥Ôp¶ÌêÝ\\ëîó³·µÔ`~’ê+ŽœÛûo¤w<ÆšÖA®)cAM.§ˆÏn¬%÷¡öò‰íwaI#	mûGMu%­ÏTôõ±Ûïìi»m#ÖB¢ä²ÀÇ¼ j§‡Ê­Ë·l¸µpêvO£4Õ»Èn ÒAÄpëªmU÷;‰®Ý ˆ¤\n\"­˜óãbÁ­³1®\nÝ çýã^Ã¶ì;³Ô0é»»5°qO>1ü±u=ÏøÚkù£M\0û‹3tÛ›\\{ÈÙ4 ê(ÊàŒlyµ;v=/+üDëúfá¿_l°ÚnÛîÅ°ZÞu&És,÷P]K4Ú!Gj]$e¨ÚXU‡Õã/e.D¸ä#JPÙ¬Þ»q(Îˆ¡ã_æŸœvýæóuë…Ý÷3]ÝÎÒÜÊr«Ëâ€cÝ5”·d\n)<ûŒîKffåw²èÏb}ßoÚ/·©VY¿xÎÐ²·vÒ*9ïH*~ÈÇ[Ô¿¨+0Oº+Ú?)¿nØ1eRçí\ZvGÎênší¤‚ßoÝ·>™™®aýÛ;NË·Ý•«†D ,”ps®ÛîÝÔ« º)Úí½ØN½¶[›jjV(kÙ5ÀüæQm¾Ísd»{¯*QD–]^’!ÈSËAú}³Î»h2Ž^ûäŠ-îÕ¤>Ž×açõy\"VÑÿ\0¾1¯9o\n}ÕøA¾áã/ïb¹Ý7€|\\‰0o;oÿ\0Q×Ó1	å8nPL\"ÞÙÊ‡¯ »¼ ZÇ¸¬“×‡-g¾!‡n«±¦e~ˆ%¼©tW-_Lo÷¹·Þ\'^o­rHY.¹É^Š\Z:xF’0‡“º²S—¿Œ3.å«v‰\"ºßmD[x-ÛíVˆ{1®êèsQ:NsZ²ÜŠû¿ëib\ZmõXZÚË(ªÉwÎ.t(üïy7³]Í•§´O@¤õÛF#kt¶²=u™×¹;«Ë¯| »œÌcšnÚ¯ÜIÀpÜó¤±zâ$ùqþºzp—úk­vý–K¾Ÿê8Z÷¢wjC¹Ú©ã<â\ZV¡¯hóR›¿/kˆ.[ìÜ^ïOAè33a¼Tsm±GÏë0æÇÑtWîk%ÎÍyÓ›¬ûNçõWVÒZ1W}`\rv3¯ïQaj(ÂâŽ©émmÍ«‡ˆ*hyá??ÞD·$sE\ZÇPKv±_üñîmZÒxÍ÷@`ëd’ä·‚Rj\Zã^Ó†n)je‘ÍF3ô†ÁÔQt×¹M²êM¢ÃuYwÙânp´ñ§öpÚÔ+¥*V¼1àomMï1eÔÉØÓCŸQžo‹[ t«v{ªg]_×‘õ’ØÃÓ;FÔb˜Mí{m³Á3V]Í+‚‡UH§1¿´Ø-«[¾£GÀO3z·¨4\"ãšŠŒ7°“¸{‹êË{­Ý®ícu¸*æÞÊÊcBG€IŒÛýŸ1¶[\"Œ_ðž“oŽÑ€àEz¦{k#_ij­M	Ç­P	™š¨#Lm7QÛÙ£Ks+,PFy¤r@ñ’p¦ëB¡\' \'Zvf\0G/ýÇ]ZÍï‡yX]dš´†îUô^æ+H–Cæ#IñŒx¿*mW×ì©ž‹zª}Sqé©znŽ÷c´õ®×Ê_Y^¥–á-Äð%¼¾ÓE±2‚ŽÄÇÑòWì·ÅÛÍhÓI¸tÇkh¢+ŒÁ™¿^®Ý­îÓ¤/¶È6M¿bi\"·Ùíå«ËFiK±&BâŒ´yM}g•%«iã.n}£ðè¤óû±qßÃ  Nãû‹2,]q¾iÓŸMß,mžRÝ˜í×ŽÇ\nù•Ãqí½q}ØÇö«¡[¡O¾ëk6ëÎ†ØºÆ6tÙéÓûý)©–0^Êb}èõFÌ{TbÛm[{ïg%~Úÿ\0âÜ}r·irÚ¿ìŸ¢0íÏ»‹>ƒèØ‰uŸq±ên¡j×$©ì0­{‘\reIâøÎ_Ôø·~È‹ìÄû~ŸÂÐœjýd^øv}³¯:¦ß@	çwË\0R‘¼ÌÉØ>©1ê< «m‘¹¨øLMë²ßeé3/¸¶‚5RªxÖŸ>7@ZeŽI5ƒ÷&Am@EkÀSæÂÛÂ¸Ýžô^e5­8ç–<½Å¨š\0Éùrx‡Ð`µOÜû\Z¾^±û½&<áÍºàÙ„w)]b+Ì~ïIˆ\\äÅg•ÌÄk|òý¥0Yhi×EõÄzL²’#:¼š~ñÿ\0^˜^^¶×úGýzb,¹Â…ý_Þx??\r_ÜÒFÎ‡×ÇƒÛJÀ¼<š³æ7šxé†éA\0r‚æ\Zž¼Çýtx²å($ueÈHôþ>lZLõfhÎ¾c\r ’Í:d£‰ø1*º%Y´ˆ›¸o×½úÁhÄYDãÙ£/|“BrðáÅ³ \"úŽ“5M×bÿ\0¦zrÌÍ0šãu“p¢a©t€Àgø\',eÛÜx×O@š,øv‡\\¶m°ÍË‘$tj372e\'Sœè|0W½L\"ÂÕqŒðìÚ#¸ãüækøÆVÌ¼m]É?¶è:NZðÍ»Õ¤ËTê[v‚I´ëÌð|lÚl\"A1ÅaÈS\'xÐW5ãO\rq,Æ°€EÝðmêÍË6}ª~|pÈe­bmÉ¬‡@$WðøñFŒ¨•®Y•Wˆø#ÂŽi¢w¶Ê‡Q$öwÁù\0­Œ#.ÿ\0§QÝ‚i—_£æ\n“WØ¬–RQ•J¸¡­MAñŒ#q¡ÑaÍ²Õ¶Ù\'G\Zu©CÄ\n¡¨9ã<\\ÒÆ<mjQ+nqî—\nÞÏnüªTM)X!~–b‰þv[­À«Y^$DK½‹©7c/²*2FE«FOŽY1ãWl>šÈÏéú½Õ™îmƒ—Ñõûé¬öÆè>£ƒª:£©¶û‹XKkšÐ4Ó{5É\0°d\ZKFB9Ï=\'ªöÜ»¿F^‡€á!™. DLsÏÐq<`î¦÷ÍÐî“>Ëay¼Ï3-û¶èÎ-F…y4­><5þì¨4©Ôz§Â)þÐÎu0Ò:O§Æ\0¸ëïzÛÄÖñì»}FAzLs^K”òjâ®˜Ìßˆ¸£ßÜÜ Ó¤†¿òÿ\0Ë–6Öªuk c£üßóO7>‚Ünî½âu…îã¸®‚-¡4–…Šñèfš¼iL«Jâÿ\0¢rˆÌG (ôôÊPoá¢ƒÍI_§lº{ ºr®v†º\rÈ,[?Qî-Ì‰1Va8ž+‰ôƒ§¸Ü•PÙ\ZÖ˜bÍ‹vÍ@§NÞkéÖ\"×¯Ý¸(Mz1y\n|>3ž¥Ý^k6Ü7‘î¬i£[ëHÞÕ·¦Š†<ÐU[ºxá…¶)€ã‡z8Ÿ`àbíp×´xcÀS§‰Ç/iâ$o·ö7BhUožâæKMºí§²»t`À{5ä0ÃÍ[Ð•˜èËÇ‹7hé®>ªaÑ—N]r‹Ù\Z©‡®¸ñ®}õgž­Ýl¹;|¶>Éw-¼ö­ìMiy(“¾ÀÆé%µÀî­R-Ù¸Fxó<GÝá^£+Ú¶XrÞãJóí’¦á´µÕõÒôíœvÁîn.Ø\n^@Þ«—gí!j~«Aá–\"ãÆ•\'Ÿ^^óõMµ\r…h.>¬ýÃë=Ó}oïë¾Ûw_Þ=:ÑÇ2]u\0šT²‘[4‘Î*N–+«ÜÚØ¹†,~èú)ñÚÝ_·Ž­J>ñújîÂQ{×ˆ”ê;&êC+¾‹K‰D[rDì¬!±\r2ª•\ZMÇ1¼x¹ò¸Ú=UooU%œ}õ×ë¢û8úëg÷ç»O·Ê“ìvö63Fb³:ÈªÂÅP´§f_$Ejë$ŽˆãyíÆZh\0Ÿ„JÚÖ_›hEI+¹o\rÏz\Z\ZÓÆ§\Z·kZTõ.ù•j”­[cî“ßu%…›¬·;­öæPeilwZƒâÑÞ§À$wBõöŒ?ê{ÌÝ²\'£¯ö›¤]ro|¢š\rµûÌƒñfÏãÀÆÔðÐzÖŸOÕƒžµêjügÃ©æ¹ìD×Iõ¢¾·O&¢TmÔf´ê?Â[õ,rjõã:c©o™½\\È\r\0(‘ Õðeî¬ Êií.¿8ïï2¡ËPôÀi\"¼|øÊ6¹MC{	%ÆáÇÎgnJ1XÚ f2Å•1ƒ{ÀŠå÷æ”ÕZ´ÎÇ\" œ«‡RÝ\"Wn\Zá/oP!‘ZÂÄN^\\:‰X“œ	á®gûÔÄ†…†akUZ	šì+ú#L­ÅÜšm-%ùIŽ¤ðÏ³	ï	*@ÌˆöÔ€ÀžrÎýÕ²XõÍîë³LMÒÞ<¶ÒP…1Á•€4`hAìÀ¬muíÂ8áCww§p]\Z‰k~“aÜ78·}–q¹§»ÛŠ=mîM5(b”\'5Ò~vÜ\\D)sìäy‰Û†¶ìßÚÌr1‚¶=×¤mö}Ëv}äwÍp[M>¥1…å©au/—UÔ4Ó0>1ÅkO`#6“ª¹ð‹{¿NlÚ8ÙwÏlº‡;»aoq\0hó$êp›ZÜ]-ÛM#¬zÅ*¨õñžm;Ól-ïi]	Õ`Œ³V”u#ÀÂ â÷¬­Áá·ÚÛ^km¬f°¯Uí!ÔÑÚÉi»GÓ·s·:]ºí$h‰\rB9#^\0ƒ\ní®î,`WXF~±5.[Ûß]JÚ+Àåê2Äðt‡HIau¸]þù¼´Ò–6ñ¸·çTyéUz yrÅU¯n\0h^\'¨NÆÜ†\'[p=f#õíüÛý¼²Ë[Ë‡,åû^GËÈ1«åöÅ´b¹L­ÍÂ÷qÎ1õîë`Ý5ÓûRJ¦kîìQ†Š?w2\05ña…†kípäH¤wyE»H3mQôöçÒ6örÞÇ±ïq×“{*;Ã:Kõ[H:H¦D\nyñk¦õ«ú”kSÀf>¹È,½€¬ts9ªw´\\t÷Lm¾É³nƒwÝïù\rº\\Á¥µ½¼HU.fbs ¥1mÝ½y]×BŒÌ“ñmíì°VÖ[–S¾Þ¥¾Þ.d2C#¾¤-@B‚^AH–µ¦yÐÍ‹ïïc¨v½÷néë-²äJðíp]¨4KrS¼hHÌeŒ¿+Û5·}B•v#ª=æ;¥akN:P\\I÷s}·ôçVlÛÎí9·±³ºŽ{‰4³…Œ5´ f4ð?2²×¬2.,A¤Ët©yIÊ¢ñom=á]õFÏ&«%»˜ÀŽ\nÇ=«»UYX¥Ôæ¨òŒ-þÝ]¸·pRª=D®1w}á^.˜â}b]Þbè»Ž£‚û¦ï=ŸcÜsOG(}¹ä#™îzÀ™•åêË/,X;f—YkLGk‘è¯LGwà5ÐQ¨­ž³ÌtÓ¢Eï©áÞz†iöºþè·D²Û5TRÖØiC¥¨F¬ß?-åÛCnØÞ=¦ë0{ýÒÝºtw²½BCî×©í6Ž£K½é¿ôKèåÛ·d¡jÚ\\®‡$($…:^€W,[Ìv­vÑ	ßRzÄca½]½Àºp=F î^Ëi½^G·Ü›§t¶ºI\Z¹\nú\\+\rC:5m†kb¢†™r–f]X\ZˆùïStÛz·w¶Ÿg—Úm#Ûì –]$† ®´uS‘íáŒÏ+¶ût*â‡S|®ÿ\0ymï¦£HºI=ÖõÓÓ]U´]î²û.Ûg#™çÒòi\'QÝŒ3Èà1_4Ú=û/ TŸ®w–²ÛÜ­Ç4êˆ»”îû„÷Qš,²»ÄÜ*¥‰<l¢é@t™—n¯ˆHšFÓÕý3Ö»-–ÑÖóK¶ï[dBÖÇ©`Úí—ÑŠê!Þ`ŸUÓ?V\rÝ¥ý¥Âö;JØ”Ëjzg¢ìnP-ì`<9vË¢únöû¬ìžÜH,ažk‡:eZã4Þnn`¶Z½$}¦Ü\ZµáN€IŠóºúßu¶µé®™öÞ”ÛIlÀÏqpâqpÃ-dd\0ÉF5<³Ë^Û·«™àÝ—·ˆè-ÛEàs=\'¦	÷%¾mÛ¼-§xÝæ0XZ´<ä<ºU¢u­3Ï`Á|ãl÷¶Îˆ*Äaí»MÍ»UÜÐŒ½³È‘”î«8liYyk\0¸LÓ½Ý{Ì¶éþšÜºS¨O¶=êíW,=¥Äð:•^$G!j81¯‡gÌ|«Åº.Úï]Cï\0G¼O]°ó¬\r»ƒ\r#O¦~sÝ/ÚYŽ™,pÒÐ ‹„\\å¡´§4\0sËÃ‹Ã…ò5Rk—[±·º]»§\Zçÿ\0[ƒzšö[]enöúõéÐjÙSU|Xóvöw?^×Hì½5öÇoßVÙ‹`öµV™ÅÞíj…5-\\zEA<å½³“XÏîÓ«7NŽÜå¼6‰´ßDö›®Ù9¤7V’úHÙ\ZÕjd|UÌ¶#p”®–ªÃ53Ðm|Ñ,5#\"9ˆÍ¹t/» »öîë¶D’¬ÛNù«4ú«<+\"Hríðã!w{ÛX\\¶\\ýä#Q¡aFÚòÕHäÜ%‹×Ý×ºQ&é³n?õZÆ¬6éÄ\r×c)s©/ziêäÏC½Öû³pxVøãVnŒ0ß\n·¬íñS­ør\\Å®oé¹Kº^±¸¼º•ç¸ÍYä‘‹3á$×¥vˆˆŒ¦SßrÕ&h½o×÷EôÏµ]‰·=šÒò-Ò\0’\'%æ¸ƒS(VªçÜ\';-“¦âó0ÁŠÓÔ&•Ûêö­€q\0×Û*u—Zt¿^ûº€uç²{ÂéÑ¾ÝrñË(Ý6úšC#ÆŒX~«È@#\"jj½±»¶¾|1[O‰uº:m.­Ôíw×Þ>PW@uÇLôß»~¼Ûïîù]G¼G·Zí6|¹¤Ž;“-É.¢€¡i©\'³{loÙ4ì¦¢}˜N¨ðÜqjSÛŒ#îsÞ¯Lì[ÝÖÛÕèfé\rÞ^ç	\Z‡2ØóíÜªD‹£ÈçóU7PX\\SëÀû¾»:[bºsú\"R{Ï¾ê­¾êyÀö«Ë£ræ¨\Zpî¢…QâfÅ,Ú”` ž·.0ç¿.¯Øz§¯÷ç¥.ý¯cÜÃ/.H[Y·ŒJ$D ‰ögá8oÉ6÷mí•.\n¯ÄÓÝ¾T{ÅÆFd“Ù³rHåIÉ+Lz³¨bLYn\0h%;Ëh£ˆQ{x\\)¸°¡rŒ[rL³¼\\•:\0Ëð€·Zp”.uKZSÅàÃ8@Ö~ÅÛcx·ãþèŸ.>P{Í×9/TãwgTf1½2þê˜0ÊH€ :æG¡9ÿ\0fCŽ–×gK]­µš0»Â¬dD²¯Ýÿ\0ðð´/bµ‚×„qÿ\0W&Iu,1F}_æãÅRY¢ÍÕÅ©‘‹Ç‘\'êG†ÄXè®22ÖL„¤oSàDÁÏ	LCssó#…œ	_’‡D|X×íÓ± h¯k(7¬7ÎŽ²¿WØw&šÄÆ¨€,	R¢”¨ËÛ\rËwåoø\0ö3\'ßz‚÷w¸0[`ÛEtD-•uxÍxãU,ü\"-pŸã;ÙÁ”¢È¬(U„\rF\nÅ4éçÚ®¨×uºn[ƒÆ×sLïÒ‡’¼<ØYvè‚’íuœÖÚ.¯Ä¨¢æp({º|^Ñ$†hÙÍ×*¦yõSð0£%ºÆÞß¯ï\'yýNç‹[D¨¤Æn3#Þn.d’C#Jç>)5SQ¤Ê\"ÒÈ¡€úÁ”ü,õK‘Ó÷&¥o@\ZŸÂú0*e0;¢—\'¹ð6øFÏ¤€<=Ý5ê©\'ãÂw!Ö\\Ù6É¥f`D}Q_‚¸YA¬#‘5˜ÚåªU²¾¬bŒ¤Úºwh‘‚0«^èË¹r=m#ýÄ%¡’#}ÙÔrº™(1™¯„™ÈŽ*3î]Æ9MÄ–óOºViÑ¤š¾F\0aË,Ã1‰ßU5á3­›{¸eMÿ\0uM¶	Ž„,äÇ4‰ZŸL8MÊvÈP}8}1 -×°¥ˆôãôE«þ’éë$YïEÇPÛLŒ${	‘ƒéE	ÔAà=p>,>»Tãpt|¾¸ƒnÜà)lôýgêM¦ÌíÉ´ì666¥à[ƒºÙÝí‘C2s£ië\\\Z‡V«Pqã†¬)Ò|F*}´÷õÖ)}ÀaãyuÒ¾ìxRT†âÿ\0¨v›P·—¾X#[í;Ûö×öYU&(÷v¾¶±š4ˆÊêXV¸*’ã³ƒ}Ò3§NŠúÅ`˜=¬Wï‘=\Zéê4…6»;i¤»»éI!ÛwèÑíjéù¶é¡¸HÛ›Ía/*G³g©Qè´Ô$­ãJðéödÇ©+P~\'•rhOj†ÇmÜ/äé¶µÙ÷˜!{[Ž›˜¼RÝO<K:º{5ùFW:WZ×AÕAZâ+SEZý0§®‡Ž9É+AVjŒ½1¯«Pá†PÍ³]ÚÉmoigw¶\"[¼—gRÁs{dÍÌŒ3C(itÉ×HÕVRISÇZ±Ïƒ¬üþCj(Ëñ=CåëçSfß6í½ö½—|Û÷]›còeŒÚÁ¼llfæŽ&–&’%bC-t•=ÞÍ`ÓN<)ì#‡©F]PÖô«Vj+Òh[ÏL|°Ûöë˜ŽÙÐÛ]—MíZÙäÝwCÏ¼c!.æj² $š“:íêßá\\ÛéÕ4]?ÓÄØ·³Ó®*ïÝvé\Z[k„y÷/ç‘.\'	TŽ*ö\n1Æ…Æ¼óêÊg_Ûøye×œLÜv˜.f3_[óî†^Ù©ÄÄ±\Zx«‡Í”liõÌñy×\nýPcm\"2\Zßp¹µeÍY¥Y\0=Ÿz†\0öi“ë¯Æ0—«šƒê§Â±¿¶²€I}4ÛæìÙ¤Ì Æž2¯—\0- P]^˜Fk5O_¥L%oµõ&ôÆåW”‡‰”Óà¼GD(²§¦wIÝÞJmÅÖ©m*H×Ú‡ÍŠ–Â¤Ë\"c@#~äºÙí…ÔÖU‘„rSÈàÇÌm+i¯ºi·]+ªžø+þšÝöÏbÜí¹®eê²1RF\n/‡ˆ/¡¡3@éJÛ…*®.H¾ï-p…æ¬zÐÓW]ÝÉŠ6c$5{…l<©…©HZšÓŒ­=ê¼¾Õk¢8^C±%²ÕN\"¤\\Æ°L·4º–ÝÊu±ààÃ†ÂP\nD‰%¥ž?¾îožrÖÑY°4§·âÁo6šS9Ê¥ëÑå‘D­\Z†&3¡¨;5IXŽ rØ u°[™jbšB(29±›uµ1„RW\nÁ¼7Ws:,“8Ô 5HÃVØ‘‡½À$»›žyõ0Z¹L¾\\\nñ\0á\Z·R¾¨Op·”\r¶Ú@éw+s¡`iÜñà6Þº¡4öR‘Ú(¯ÚUÑ:HÑjÕPÁˆaòá¬ÊÁåY5ü”°Û,i¥¤\"F‰¯sçÄ!,ìy	Í@ sƒ7ëÅ“«6Í¶0\ZXùjb\0nõN/¶Ñ-ÊMÓGœÜÍ.åÕ\nµ/R$QFx¢¹y5`Ê¤X-ÎÈk££Þûk+î/.kYO&<““iþ:É%4\nJÝu\r«+{Ü—qÞ–Édÿ\0Ó`s]¢kÔÅk§±l¢ÒƒW§Dá­œxútË’Iy½{ÕiÌ—0Æ\r©R8Ù¥KvPb4x}·ÿ\0”¶¸Tñåó”·\r»yéÄk[¨Œ7êê×\\Å“XâWºpK{¸8N¿”½ÛOa»dáÂŸ8ŽÌ,îyà€ßV23?	¯i j\0öÿ\0Ë-[n(Iö|ä&ï\\rHä†rA\0pøñ4¹NèöŸòÊvÁ \'Ùÿ\04åå³ä\"Úª¿Tx|¸Š]§u˜ÿ\0–@\n¼”šJ¦\nPjQ\\Î‘ŸÇŠ°½Nêÿ\01ÿ\0$]…ºâÍü£üÐ´¢ÅéŸt}8ñø\"ÿ\09ÿ\0$-€]¿žG,ubÒÈÞM#ò±Pw@à‰ùý¸Eý)ûoü‹ýÉ^ms#Š×=ò±Þ&óþ¿ÌoíFôº‡õò×û°d6¶òÝ\"¤’6¦áÊZñ1\"îü\n‹Vÿ\05¿³s³	ËŸ–¿ÝŽ2Kceh i@}>²@¢µ§8G^ü¶­Wÿ\0Yÿ\0±3ÆÄ¶¯ïä§÷à	&ÛP“Ïé5Ê5<óAÿ\0“góŸöóP[òú~-ßÉOïÈ.‰\r+O(òB§ý® n<Ò¿ƒgóßöñ1kËI§{òS÷•”›te£3šø`AþØâÏwÍèXÿ\0Pÿ\0¶š^—Ö»ù)ýøÃ\nÚ$\0µÓ2ºŠV5¯·€xÞjØx?Ô?í¦eÄòÒßò÷1cw]²y¥&âç6…²0ë†’ï›ÒƒocýEÏÚÍ+Vü¬[×¿!?q)XÝìÛuÎjº2Ûkÿ\0þƒ‚3ùÇÿ\0Çú›Ÿµiå—Sñ¯Sÿ\0A?q/Þ_í×±»” ê¶Œñÿ\0^1QwÍ²ý>ßýMÏÚDmìü¥E|{ßŸ¹”n–Í•Ä›•ØR(TZÇÃþcñ|äb6ûõ7?iµoÊÂõïÈOÜJ´të&©.î˜S[hÅúŒõ>pGÿ\0Ï·ÿ\0Ssö‘†¹åµÂýïÈOÜÂ»}–ÇÊ¸œ/f«tíÎ/ùÁÿ\0Ûíÿ\0ÔÜý¬Î¾¾X[÷ÿ\0ÓÛýÌósK\"ÇíW\0ÔzÑ±øç¸»çÿ\0ocýMÏÚËí¬yf~=ïÈOÜÁ>Ãµ	ý®ì\Z×öXÿ\0üœXÞóþ=õ?k§–Rž5ßÉOïÃÑË·Q·,ÀR¼„ÿ\0û±ïš“øÔ\\ý´Ém·–V¾=ßÈOÜ@·—¢ìyp­ÃI¶ŒÓÿ\0¨Åëæ•ü?žÿ\0·š¶­yxL.Ýü¥þô|¶Æ•÷+…ðm£ÿ\0ò1pþf¾‘ÿ\0æìFvÐ\\¹ùkýØ¨÷Ö4 n7‡Ékÿ\0î2ÿ\0[æ4·ù­ý©©ú]Ÿä_îB½<¶774k¹ä²H~IŽÚÞÝ“Š\'æ7ö¦~ôm‘0gþAýÈvµÛ­–:;‚)™ANo\Zj×Û4Oç?Û˜Ûk–Ú´vþAþyn³[¶á6™¤Ó\\©\ZÓ‡ÛÇžÝ›¢ó\r+üÇü³ÓY[z\'Ùÿ\04¨’F=×ø ¥€ü‡·å,Bs>Ïœ³¼²csá¤}8nÝ‡|iïù@;¢ñ…Ú ˆK*¶…‚?ËGl2-HÊ ]XÐ*ã}}\\¸Ó.ÊŒg]ó€Fj¹™Ung½˜#°Ž\0›‡¾ÚL1E¶*!˜ì#ÒK‡9p\0Sá®4ÞÃ8Ôuü¢¾\"ÖZýÙkúGãáNàzWå)âzR~µ·žˆÔÂ£1_]ô|ÕK˜Ã\Zêˆÿ\0[ÛËÍ½¼0äÆŽÏ=žéî€n\ZKC1#Q¬ç¢¥…¶Ø¹’@Ì$eGÿ\0G´gˆ~Ë‘Ñ	šŽ¹¡ÙÜ[F€—ƒ,Ïßá6.&éi¯¸ðÿ\0_ˆÒ%ªaÛmÉ4}âSý~¢O)îWþ¬úÈiþ¿D‚f™§Qu•ŽÝ,–ñh¸½CÞ¦XPö†ñááaè	Ê\0°9K« µÙí·•…®.ƒ,pÀÓÈž/ÙŽý8v§)y”a/úŸrÜÄ×1$N \"ç…\0xiÄøN[H38úàKÎž¨¬$þcúúùñzu{ç{}ÒÝ’DdË“ã§;çÄ=+(Õô¤gÛ#ˆ÷_×`l%+D‘êäÖŸÎüø«e-ödJ¯Ývð×^NæRËœhuŒD	åä?œÂG8ÀÊ\'uÑ,N;œé0í£€Š¸Æe›”‘ºâKg	 -T¬‹^:LƒåŒ­sm3 ,V¼D €ŒE¤mä×J½¥_£|áÁ–à³f  žÌä	\\XUhÝ±mQ@Teñ-§Ã\\­íU3Féý¶Ý¥S]\'°†Í™ÂWaÙúon]	J–B¿>2n©md]U‹¹Ce@ª)žZp™8Ça3­ó}Ýa^JÝs’<‘%nòÓ€hE<\Z°ý›¬¹ë*Ù‰ŸîÝ_*j]Ú	ÚÐ¶§‹) cÂ´#.={{ª\Z¸3\ZîÒ¢ˆÂ²ßºNWR–öVwÇ\"Aqd¾ÇpæCVY5äõ#\"_»Ù\r³ÙÕ¨PdÏÝ%í:[QÙelº[pk›ÓYî¢šMæ~`¼¬di	4N©ôX\ZÕxã@ZRuÓg¿ç×3MÖEpöû¾]\"‡§å÷©¼îÜ²„†[+ÊA‚´nKs#l…d¡Ã‰¸j‚WÓ–^ã\"Ù!h@oNyûÄ—v±µso-íÕÄ2Ù4³ÚGµã˜I2uæhÐ¼À¡Mj0FMbª1æ~pjú\ráÈ|¥;9zwx³NŸê+{¨§¿[ˆâŸvÚ F¸—”³Ú¥Ði£®’i^8R¤öX=\'Ñ}ü#”´„(èë§{oéÍê›;³{Ý\ZÂ&h±Ýeö¨&‰ª¬i.·B­FV/æÅJªŸ«yÇÙ,Ù~¼?îŒ=°ì]?r¯Þ/+ØÈOà“;6Æ[^œ½z¨¨¥\rw*!FÙŒìôÝÖnN“ãÄ~½VOû{4™=Ò·,HPœÈ<+Š6Æ’ãÊ0•g÷+krKÜk $ðážtÅÌÕ³\0Ë¯•2äH™E÷OÁÓ½kÑ¶ÜI=Š:ó:AÄ©S‘§‹g!ÔŠ€aU#A#ŒÒá¾’úá­b–±ULIÒ£Ò á\\K\\¼ç%²iÊTqsm;JŠÉ¤ðZ‚j<Xãv²VÎ™\ríÆó= I.&*çJ#Hìx\' à%ô³`Ma[V³Š8ec30“™Z	® ¹|§-°§Í³Û:$/t[\\«êªQG„Ýªc–×:%™b–eT‚yn\Z>y÷Ã)#áÅ•ªtÈu¢Öqe9¾êhÅXÕÚ4=Ú ã‚ÓE³ gÝî\0½Ü®µ2YæIÓ°ºUDãýByIúaÅŸLÉp§•Ù²ðð.#p+qD-ª*1ç$Fúw$–šB~×j–9Lr+4+()iG Dˆµ0©4óã-Ïj>*¡¹C®öòI‹uuÉLê½Æà<ywR±§d¥Ü—ªÂÅç0²§„ŸÂ½Ú4ÆmöVG¿Þ$.^hµ²[™-\'i*èw!‰ÛZ®=3·7\0c^Q\nþêê-ž$Ž6i\Z]sI×HÐøñªSA©äb(ÌýU¶õØÝí—1±€Eˆ)¡,šYê*0½¾¡Œžël³C·K\\~ÿ\0»¹ŽyÄˆc!9²ê\0ž<\rî\\kn£ %í„VRÐµŒöNåÔ­…º$V*M’Ê©‘¡9\Z³ô0gºª•E}°èŠ¤·ÊO;FãÏ´^Nési,ï]Èà3+I¬yiŽ\rqÊ÷TÅ™Q‘÷Š´¶¼{Ï±šÜM=ÄÞÕ5Â\':Œü¼°{µ»qõˆ›3ÛKd/{)nîòÇlÝúŠmÊÂYv=ÂOe[¨K1ãž>ï‡ªY-…~Òã.çÃ¸Õ\\óŠQ°[m7Ö¶û}ÓIgsÜ)¹«K­\0\0æ¾C-æ½]GœO}¡2žoy^•×VJ)¥*¯l¡¨EE¬ôn\rÅÜj-ZññâÇ½&·`›PÀoM€¨_>.ÙÇoÄ é=ÌÈ«BjWÃLF£3A\0C–±ˆ‘ˆÌ-k€êÆ)pÖ	»ÜHÖ1Jñ8 ·XÂ.PyÜ£Ó©GˆqÁÖ×8V²å„’ÓÚYÈaÝˆŽ5Å[JŒ3‹Üå=¹†YÍ™ñÂ²Èáe)myb¿Tf|¸º¼7‹YU¦–ã-]ÓÝÅ´K	.ÚZÀX2ñÄâ¯u«Ë-¼1úú@ø‹ŒK¶Lqn°Þ™c\\0«,95‹	®¯šhÖ±×äÃB¢j”9Îw\'ŸoŸ˜V¥”Px³Ç(\'[·–‚¸Üo%‚‹†BáŒ}6¨¹ÊÏs¸9z./¤Ð‘,Ûãi³S·Ä^ºØTÓµÊá<¾êáñâÛÄ·a[NœŽ|q×Ä¦íÍ­–Ñ0©xâ”FØ1j\Zî;t\0	238»(ÇoqŽVâíä¾žKxùªŒhN.Ãfý»@ \rïRÞ2ÎŠŠØ^é`™Gv¨¢qZeZáÆv6t­£Kèµ)Û¯ak1|Âæ‘\no–Q…Õ!,êpÞØ˜žÎé9Læì/¶I\\…M1å7cú­=E¾èž%5¥<#Ž\0á,rŽ–ín±%YT×€òzä+¦a\\\rS\'ê+¨?v¤hÁ‰Ót‚¦-³¶Þ!&gn¦¥û1ãîš±ž¤K[PawTRîx†¶\'MÊˆ+ÔÓŒcìšr‚ÚñÇ gr¤ÏìÉôÞxÒ¦G‚ìM»¨úšê–ÆÂfUZ‰dö´TögØqó´µÚj|c¥ª¢¿ƒyu(ˆë–Fc™þ× üd×è„:/v½ýò¶K4¾ÊUØÅíQÐ58×p(À|e°å5ä»t\Z$zÆ&ÆSA,Ùû_0÷ßþe1\"²\r#U›:üÿ\04ƒ\n<°‹q½Í·Xêµ•Äó¾„hˆÐR‡¼pÆÕ<N×(;‡IÓ2[;i÷Kø¬ÖfçÌôW7hÂµÏ†UÆ·q~pW‡º;îÝ9¶ìû1s<×îÔ€Ë{:‡¥¥;KîÇ>ÏeÊ×Ç¬¶2dÜÙ’é0ÙÃ¾¾”•îH›ïåÛr˜à}+$za,Ø«“RíÄxC‰oLdL#¦Ã	šá!i	\n®®z*pµÓE2&¥»m¶QíÒ\0#¶F5ž5/£°	ÆM¦©š.Òšéëe¨*’G€Ï\r^ÌDS8náŠEA$•§é\0øÎX0„Ì÷©dn[Õß·óÈq¡m)\0Z¦f×Ò1”÷Ûõ«‡+Œ•¬:¿	¿Z¸$Ë¨;­­ŽšgY€gŽ)aÔJ¬GÇGùð„Ë¶VñUö¨|€àac~ÑlŠBç_¨¬ßÊ ÂŽaDÓzjÝª™\Zv€c.ñÚ_Ø­@8Töã&ãM[kuÖˆ/+Â¨àá—Í€W(ÈÌ[ªf\r«H¨í ­>|21Šœ&M½]\\[\\Ks4Ý@©:H­3i©¥iu*Ebeýâ:Ÿj´†é{dÕKð¥Â0Ú¸9€}Æ(ÈFLG¼A1ßÃk-6íÖïh“ˆYjñWÆc§ò0Õ»{®É×—»êŠÜ¶[¼ŠýYûþ¸_oêž«°,öëi¼Dôæ=«…™´š‚ye\Z£Æ¸Ó·º¾¼ÇF~ž©•ska¸µ³Ó—§®5lþûâKúÞ{Z0EvDšHéÄÕÀ?8°ÞÚ\'´¥©ØÝ²ÁÄÒzs®úì®›æ°¶éƒ=xvðóØ¸ì•=UCÚV\'¢í·›Mö—±¼†~Þë\nüÂ7×0cÖÙºDd·¶V£2Ôž4úp‹4}V´³¶$QhF^|$îcÈ‚µ³€}QæÂŒæ8¨!Fd’‹\nñ\',¤˜z\0&MïKÞFÛ°Y=¬/ëæa‚2ŒxVž	ÆæËj{Í0w»¿°³\néûƒ{¸Iyr5ÝJKÇM{1®øž™‡JôGÝ¦ke¸:ç0õ²V•¥‡	ÞÊ?`åXÏckc+›ˆÕ\nD¢GRjM;<øÏ¸í”Ò¶«œt’{W/k§T‚‹	\\”·>JP¬ö[-¦iä›˜K‚ñD½„ç0Å4å\0§Æ3\\$6i\n(·0¬0‚Ù£F¯Ÿ	-Z‘‚£Ñ êY–ÓmÛvRèÁž9^u\Z»AË<1¶BÌO)MÃè@T‚zrôKwº]/PE¥UÈ†7ª¢nÔ©·wºœÜC\n“4¬•ðhZšœ:‚‚¦&æ¦‚1ØÛÉM¤iHÅ´¡.ÍjM¥<xOÄ5a¢-Eˆíâ·¹[C/2ï˜ZHSP©.5™ª5LÍ\nHCçGµÆ’<Z×•ÌBaTR)—‚¸ÈDñ	ëŒ›¾¢ Æ×{™ŽB…–\"òòfºÏm\r6Ö†g±g$ÆýµM®ˆ\"-µZ$Ñ¼šÉLþd½uÐæf… 4×ˆ‰;õÑ¸¸šhô¤3‹­JÇ»I¤¸Úš²›=Í½„‘ÁýîAš]lK1ò)åÄ\\Q«°¶û¢¹_hžÚÕ£–H@6évÊ/9¡f¯eh¿‹…JU¸´5Âì4³,.á»Ï=´[ýÊZÞÚ-ÝÕ@BÃ—£à\'ÑÀ‘QI	›gp–^ß\nRuºßìòÅ}¶ûTQ[ÛY[ÚE*üÙd<­Üð,K‰µjáyk¬§Wjµ\"G¼ïRnÒ_mÝŽªº†=ºÓRˆáŽ\"‚>h¯¼\rbË•_ðu]pO¸®ªfE1»ï0ì·0m‰¶Ã&ã²Ù+Çs<”Ñ)jtý|\nÕ£}MÍ}—h³]ðÂ)^Ò˜¡mÕ[öÛcqsiubîNq‹B2	\\P•\r\'ÛYºô~¨¢n.Ñ`Oo–øÉ{¸\\´ÒÝWŸ#€ iìP*\0ò8¶VßermåÇg<ëç’ÜsP¾§\":ñ\0Šx\\·”©qm/\"(£ì8àÔÂM»€1&P¶Ý75’¬ÙW®†,/\nwn´7Úd4QÙåÂ®úM&uÆÓÙÅÜÑCm&ƒ«²˜…ƒ$E»¦mËIz<Øz”1¥©h2ÂÑ§›S.˜#:¥o›ÏŽ§ËAN1Ú#ÉGq\0¢¯qFZšÌÿ\0\npÓ8î Ôí•|¸¡á‰ÂM Á–³”ˆ6HB7sÓÅËÃ¬`µ¶H¡Ôïë;Ø}B Ìk æ@È•î‚+äÄ!Ú‰Š×ÔiO£SJaµM‹x	ogZ-4Ö•ÄÓžîßØI|¢”îòàÈÔÂ;²·ëƒm`Ì š½/³ƒV;uô.¨W÷´mY1BžLB^SQ3¿^Äa/ó¤EX’Jªpãbšš™soPî^s«ÅˆÌE¯\Z\n,ç{kQ·°Ðy•îÓU)YÛ-~\'DRmQ+#Æ551âACJOH¸È¬®V5u’{ÓJËÞMTƒ·ë¦{%C*“Å°žèFv–èñT}jçŒ±zN)Œ\nÖúCªØÝÛŽÄó¾bË^Ôã|‚ð‹‘q0¢EpVRTãÂ[föðÒ \rªÊhÒÎk\\\'ojŒ*fÝÃA9Ý¬-lí£’(èOon)¹²–ÐPIÛÞgb	kW¸cä8È±Èóà!}Àd,{z-ÏáD­w¢Õrá•xcÉ™§ìïwZÝ´åL^øªÃ’n¶Jõ2j§7·(ãK-Â{ûæËÃùÊù±OÖ¤¯€þèçûL¿kç8ð‡¾cëÝ>áèŸ&\neÖwÒß÷ŸÛÅ›/Tƒ”Ûöÿ\0ÑÆIïo³.ÁÄâÂV†, ¢¿¼ßð?õ‹‚mr=r[„Ë¶ÿ\0Û‡ô¿>4Ï¿·ßÚ<Éü‘Šý‘¹Ê°vù05’rƒ7Gñ±uÊYe³‡Á‰l¤õÓ?·[ÿ\0KË„÷ÙÖ»Âi}WþüS$ã3mÞ1ÝæbØx?É†Z$°¾á÷öqÉÞÇ)˜õ7Ý¿Ÿ\Z6ò‹ñ™Ýç¦pÀ—ˆqQ,a;?O3•ijOŸò^Ûø>xaœc‡Œ|gÜ‡I¤ô¯¥›åÆUé§fl›WÜd¾sY\"ï¼/ð³ÿ\0ŽÜIîËø‚`Û¯ ÞC‚XÊþs*ê?ñýùpçÚõD¾Áë™õçígÏ‹§zQû°Vã÷oå8i²Š®pM‡í‘ùq{ÂMîé†·oØÿ\0¾\\={»³Þƒâô ó`!Üf¹îÿ\0ï¡ócÒm{‡ªyß|uÏÔÝ\'û*}‘Œ\rÞsÐìòŽÖÝ˜Éi®°å§òáGŽ$ã©?ÂÛíbvÝù]Ïr~*÷¥ÿ\0|ËýùqìGu:¾™ãyúþ‰c¤þæçÈ~Lræg6B6muiöGÏ…ß»·™ŽÛÝ\\ý”þV3oM{9B›‡ÞCö‡É€[ÈÉ|âÀý²Óþ#çÃ‡(¥¾ü`¾ýªëúL{£®íKªºÿ\0F¿Él7´ãÞä: Þ…ý›qó+½šËí»¦Q_ñY~Û\'6S>Ög®6_Ù÷ñé&=ïdÓ¹Üˆ¶ÿ\0÷	þ¿—Ë¿ƒë˜)˜õÇ›¿Ø­à¯~UÆjþ\'¬AÜî¯ü&Ùf›ú&Ã—{ë·Íº¥í¯üñÎ?Çá\\Q¸ýž_µóã[„I¿\\°ý£§¿âSý¦3^z3ø±\nõŸÿ\0sÈØŸÓÜæ:¢ñþ!¹}˜~\\l\\ï¯XŠXî\\%¸‚mÿ\0nb£¾e¸Tçxÿ\0¹6¿èí>Q‰~áë15üfêñmÇññ(žà‹‰ûU—•0FÎ1sº`­ïÐéåÁíAXïJ–Ÿ°EöÊqvÌÀÞüV—Œ_kX˜ÌÀ»§Ôòåwf®ÛŒ3mþ¾AŠ/˜ßˆe¯ÙÚ_—8ËñƒwO¾\"üØ3f!me/mÿ\0²]y°6ü_T á=µàÿ\0gç³wL»æ$Ò~Õø¸[ê•Ù&é÷	äÁ!LàgûÈ|˜-Ìã|½\'ÝXe(exÿ\0d¸òb¶ó0mßfçó˜`qš‰ÂÛ~â?.	ö¦nç¼`½ïöñäÆ‚e46ÉKný¹|‡åÀÛ½ê‡Ý~>~áü¸çÈL5ÌH‰ò¤ö¿wæ?6x;™È·ßðø<¸2÷D6Ï¾`;ÿ\0I|‹†[¼&½©ÖÛ÷O4¥ìà¾ªÿ\0í`ïÀÏ/üC×‹cœÚšFýÀòkå‰åüß9ûé^ùþ\\0½ÏTžIm?±¯“ç8%®äÑ¿ß2¿R~Ê˜CÜ†Úg6ÏÚüØÊÚ~$Ò½Ü†·ØÎ=\rïÃ™öûÐ<^ˆòã;„tÊ;‡Þc#yœ5¬¥høa²a¸É°Ì¬ÿÙ',
    'GIF89a2\0Õ\0\0ÿÿÿòòûææî¥¥¥NN¸{{{¶¶¶ïïïÏÏ×ÄÄËïïøôññÔÔÙffÂ÷÷÷¾¾ÂŸŸÙ××ð­­­ÉÉê··º  ÙããêRRºÜÜá††ÏYY½õõüèè÷××Ý­­ßllÄ»»äÝÝäXX¼ææõªªÞÃÃÈÝÝóÌÌÌ‘‘ÓÓÓîbbÀ••Õ´´â‹‹ÑttÈ………ÍŒŒŒwwÉ¿¿æ™™™½½½ååôËËë¹¹äÑÑì²²à\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0!ù\0\0\0\0\0,\0\0\0\02\0\0ÿ@€pH,\ZÈ¤rÉl:ŸÐ¨tJ­Z¯Ø¬vËíz¿à°xL.›Ïè´zÍn»ßð¸|N¯Ûïø¼~Ïïûÿ€‚ƒ„…†‡ˆ‰Š‹ŒŽ‘’“”•–—˜™š›œžŸ ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇšÊËÌÍÐÑÒÔÕÖ×ØÔÛÜÝÞßàáâãäåæçèéêëìíîïðñÝÒôõöÓÙùúùòýþÿì”@PÂ€ƒ*\\È°¡Ã‡#JœH±¢Å‰	\n4Pc3d CŠc`ƒÉ\0(Sª\\É²¥Ë—0cÊœI³¦Í›8˜Ü©€ÂÈÿŸ@ƒV)™³¨Ñ£H“*U*À€Ð§P£‘°a©Õ«X³juiÁ©Ô¯`EJØJ¶¬Ù³0-HË¶í°±hãÊ›TAˆµnóêÍ—®ß¿€[*À€w¯áÃ¯^Ì˜îàÂˆ#K.¥¸±åËZOÞÌùSeš\nˆîÒB‰\'(À@uhÕZ‡-Ú.m(gÓ^MÛKÚ­ÄvÝtÊ\Z¸ˆ€×ž+€à¡9ˆ\0 hWí¡A#œk×Žr„	(§k¯ž²‚û\n8ÞW@?aRÖ¿Ÿ?€û”)ØÇ‚fh`%ŸÍÔ”@W0@ª€@\0\'B\0Ðÿà *ðá„¾}\"9¨’@\r\Z€vµaz\\\0A ‡cØ@@øXw€p\ZŒ€#\nh\0\n8@d\0G^€Rà¨AJ>úØB—T@˜ä7f—eàcJ\"ø(Æ)ç\"	ÊÔ `€\0ÐÔ	0¨%Zhƒ*!z§‡.¦„\"n€gJ‚ö‰¢„¨”Á(Õ@”\nD	d—¡6€œÖ8*á¨›¢$ŸÀ¥­˜0Üà#|@\0	&ü\Zì°jâº«\n&¤\0çœÐFH1ýÉ\'…‚¢$h	À¨gW¤µèà‡©&®‹j›ÿ)¥]ˆÒ„¶Šk­Ÿ6p°þ$RN‰*s¨úxÁ(¹ŠR(\0kh@\0\n·òšl\0»~€ÈPñÅOŒR›Û@˜´$—¼µ0-(o*ÂXhÁØ ¹{nÃ(·€2ÊM\0éº¼âž(\Z×•»)áëÚ‘õò›Á¿Afç£©ü››ºPð¼HÜc”w\Zf&L¼«(­@@c—}¶Ç¹’Ð¦#›,÷Üq üÒ¢ŽZ(@ƒ¸Áh!‡}òm3¢\rn(8nA[0!nì\n §qx§d @\0­DB0Óœ:lª›Vw(}5Jôë0é—¹¥×î…½&*ðÿÁ—Ì@»í¸Ï¬{ˆ@‚°9ÄM÷ñÈŸa·K‘£„âÊ)-ž§9Ÿ»·‹~‹˜\"k)úö³@9û))KyyÁPG­/§#H©¾àë#§ Áëjj0k\0–Gœ£Ç)¨*€’\0v‰€ÉòÑ@ƒg%ïäÂò0CAÀ¤`ý]Â	„Í¡¥0Á\nšð„‚1ÞWÈÂ%”…0<áTØÂ\ZÚ\0/Œ¡/s€@æ†@awHÄÀ€¢…è\ZŒ\0 PŒ¢§È  ‰KÌâi \0`€£ÇHÆ2šñŒhL£\Z×xÆ¸ñpŒ£çHÇ:Ú˜ñŽxÄc\r°(gøQ÷XÀ>©\r*\ZòˆL¤\"ÉÈrò„Œ$?\ZIIEÒ 1xÁ\nÀÉNzò“ ¥(GIÊRšò”¨L¥*WÉJM¾\0“4€G–¡ÅZÚò–¸Ì¥.wÉË^úò—À¦0‡IÌb\Zó˜ÈL¦2—ÉÌf:ó™ÐŒ¦4§IÍjZóšØÌ¦6·ÉÍnzó›à§8\0;',
    'GIF89aC\0Ä\0\0ÿÿÿ\04‚™À\'S•×ßë:…÷ùûBŠíñöXz­@‰¿ÌàÁÎáßæï«¼Öiˆµïò÷?f¡ÏÙçŸ³Ð_€±•«Ë/Y™=e ¦ÈM‘¯¿ØOs©oŒ¸\0\0\0\0\0\0\0\0\0!ù\0\0\0\0\0,\0\0\0\0C\0\0ÿ  Ždižhª®lë¾p,Ïtmßx®ï|ïÿÀ pH,\ZÈ¤rÉl:ŸÐ¨tJ­Z¯Ø¬vËíz¿à°xL.›Ïè´zÍn»ßð¸|N¯Ûïø¼~Ïïûÿ€‚ƒ„…†‡ˆ‰Š‹ŒŽ‘’“”•–—˜™š›œžŸ ;G£(§¡j#­¯#	®¶$®°#º\"²¶¥(À#ÅÇ¹®#¶\"ÆÀ¸$¶É#¶Í\"®¸²»\0å&\'®%\n®\nô#îðòûòpE\r€-T\0¸*`-@^³ îú,\0ÂÛZÛ•1À† º¢5­ZEWGÿ<°u€Ä7[³\rPð ‰q&\ZÜ«h! …“Fè<ÙSÏKX²²Õr8Ba€#Š‹(« Ól%f¦<·k&ÔŽAVÐšˆ—GÌtum\0hQ³=‡óf\0¬#\"¸Ú°`‚¿l?lÐ aƒ+v\0ô\nîûw5E’`Ð+ª9Ë¾f¾k\"c¨^¶TY÷\Z\0È%˜5ISe‡Ï¼U¤VWµ+ÔŸOÔ!a·^\0\ZìÞÀA€Þœó\0®G\Z°p¢KÀÄ,q353‡‘·÷É•u\r$0piÙ‘\"f\"\\Š=@n\0/qÇV1œ]¼\00¸j@B€\Z¸\"\nþñ7B{übÎÿ\\Î0Õe•QdKwÛíËnêÒŠBTÅš\0ÌdŽ+-­Ô\Z|%µ›‚)X(K	¸²À‹SÁDã1°\06E¢tèUöàfÀlå]l&|7Âk»èâÜSÚV‘9¨%@@(ùØÚZÉ‰tÑ}G\0Xf\0Fe£+cž™¦«I¶¤+Y]4=iŸK‘‘€JF£‰`b7½\\0R”Ö€(@A\"z’ƒ(¶æ~qêÖ¥9\"&B`ÇÕµÀœfJ§|T\nÀR[9•SÃ`ù–u\0¼––	ZPAW·‰\0ÖÐ¢èfË\0ÄziAŽ^*¥\np@Ð\0®P ÂÝoð\'?\"<í´ÕöajB\ZÙ²•¬85k”&Êg¹uô‘-PQwá‰ãàj7\0dTçK=Ii°–VÔ™ËÙ¢€ø“p<\" ¼Ór:F0IÀàÇ\0—h%¹LuÖ{õ.cK{\n	ë²&8õåLÇÚja¾\"\0\Zd`ó›\0@ X<xå¼³\"£hg‚£ˆBÀ—.-=‡ŸV\\BŸŽIÔH½ÊÖ\\wíõ×`‡-öØd—möÙh§­öÚl·íöÛpÇ-÷Üt×m÷Ýxç­÷Þ|÷í÷ß€.øà„^B\0;',
    'ÿØÿà\0JFIF\0\0\0d\0d\0\0ÿì\0Ducky\0\0\0\0\0<\0\0ÿî\0Adobe\0dÀ\0\0\0ÿÛ\0„\0		\n\n\n\n\r\r\Z\ZÿÀ\0V\0p\0ÿÄ\0®\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0	\0\0\0\0\0!1ðAQaq‘¡á\"2b’¢Òâã±ÑBRÓÁr‚Â#3S³ƒ“£Ãäñ²Cc”sT¤Ä\0\0	\0\0\0\0\0\0!ð1AQ¡Ñáâa±‘\"qÁ¢ÒÿÚ\0\0\0?\0úÀÿ\0“s¸Ä¹½É|hÒÖH¯XH¢úóÿ\0Ÿ‡dÏó›åGççÛÿ\0$mÿ\0’~&½GÇ\Z¢)­þŸÑÃ¶gùÅ¿»ŸdGñ“þ‹ñ0\"ôQÐêG7¹OÑÃ¾œUû™÷Gñ“µ›>0ÎXè$m{¼cEèØXðÇRÒè­r~:NŸãÍX~^S\ZÆ¿åmŸç9#lŒuæ8\rÓ“÷ZYþ¦³öóoíeÝ÷k\'Ä9äF÷2…ÀU µÔ¯î–þ¦óáæÌ¿+8ìû¬`óæpÄAÅÎÃ„.‘Cô¢žŒM_ÛÍÓòg(ºûù7ÿ\0wÎ5ãžo„Ÿ­{c.å³‹ÇŸ¾uð@¥ÖÒÝk•\\¾(ÝNŸ$í¶°ã±\r&W[¨ÖõåÃÈå™J1Êýóù­÷j~8_É)Y&$åûLê(œ!±šV3râ$æ³¨¦aQ)D2ÿ\0ÜIµQJŸÌ1Ñ†½Û$K„7œ~ma~ŸäœbuÖ>¾çç6c”Çtý=­ó¶mƒ“\r[#\ZjÇ1Ô\Z†îÖ¨œòÊ:y·dc:+ˆ	\0r•ý*Ÿ–{ü}Ëøã¤z.`›Ý¼9°–H\rcFAòaVãž•~>äe†·^K™q•ÒÃ,E²^/hîÍ)¦X\Z¹òÌÕß¹×Ž5¯öôt¿Mo¡à®\'×ÇÜé<ODxÝ‡šx\r²hø±Ö8YÈVg•ÅÌøûŒ0Û5oGB6V„3/\'Â\\f]áÓ’7Ôúí55®ü‹ÏýÞ‰èÜ4Ý¡–/+B®N´aÔ±	ÊÝÎÊ‰—HX`#Hío.r¸ZºÛ›ËžKÅ0R·Ä1Y×ŠÂáLX&á±˜n$±Ã%çB|ËôØLã37wþ¯ÎçSJrÌÙpæ0q÷·¯wâ;O“¸¦â‰™†ãM|‘·?ø_Ò¨ß=þ>åìé^‰›‡ÿ\0#ø_Ò¤g1Ûãî\'žÏGc4[ädl€½ñÑì·ÈIÂµV\\—\ZÏ¹˜ñÔé×ªdbHÃÛÂ£àé/,åõñ÷=1‡ÓÃÑ&âÄëÝ:àV¬$+c;Š¿r\'\nÖ¼=ð0Ôº2Ët¸5óÄI§n<]¸á½›eŽ–žHóQy2ËûÛÙÿ\0Ulß†=ËêÚSZŸT.œ¹êŽ<tXŠ!M-¡ÕQ”«LÆk­å—IÚ)¤¢W˜(Só›a‡³öyOúuú[—ç´X‚bá+Ýÿ\0N²r¶Gvxz%f“NâßúUÿ\0ÕS¿ëãî^Þ•èëæ¿‡qÃxÄ\"€qžèÀæ×/,Ë–#·ÇÕ¸á}žy™þÃAÇÊ.4üãÝ·qx9¿&g§«ÛÅÁÓÑœf¸lC®Æ;©xLh` :bÈŠÞ>YÊ5z}Lø£útú*a©ŒÃ½ï‚ìeÅ”-ÓRôAtË)Æzy¹cöx{V3GÙÉxËbqŽN]0~íºK—>±£§“«Õ1±¸\nPÛ¤¾|ÛèGR¦è–h€‚¹¡tÎêÑ‡]6í-âŠlo&Pc+-18d§“ys˜—H )kó ÃÝ41[§özé×éµ~sEˆðäÿ\0‚vmÿ\0Æ\\¯¦žn›c¤z=gø`½ŒÄã ¤n?e‡4¾GOéÁ¢Ì¹ûºxª8{ß@ÍY¡±FÇÍ\Zá÷pµ­ºÍŠ0/›ÍÏ3Ôú\\1pëµ´ÉæÞ^g¢•³„\rš#áÂc¨2ùZ¯)‰´gÅ8¸|$£,Î.1¼Wliõ¿ÃÊ½yfòÇGOFwŠkƒ@lÜÁvÚÝé„Œ¯\ZláY;ØI‰Ã: Öî¿Êñç\Z½Xe¢&<·d¦VÐØzªæ.)‘×máâÔ\rÕ™¦ki¤v·—9•Âv)•ÚP¡Oçï‡]18œ4 ÏZM`·\\V/ÑñòDõËóùñÌv,fŸ†°Ø7·õ8vâqîû¼+ZÓF«ÞØlQŸ,Ì\\MB±ãˆÒu—¯Í¸Wa\'ï1`I,œÌ\Z(Áì¬ÁÍ–èþ¯wmŸìîµ¤šå®®O2ò=–3[púÙL:0E)¹¼¶$˜sfÃwS’Á“%\Z8ÞFÛ®rÆÏ…2BZÂ±Ì7EŽ=Eq•M¦q¸¤øg·¹¼Ñ@ð,¥-æ…F­Ã©»K^Ckg³ÙY2¨„¸v:î¶ÆòÌåXÂÈi®K67—+]$mBÅRP¡OŸdÄJç6W1Ím×P6Ec+îÜcÙ?ËâTåÚëfÕ†‚\']md¯Ú¹À_qå^OÉå™¯ÆâŠÕÒ|6<Ã¥AÕ\\-ßjÆ9×Í´œ´×±sä›tÂ)llnBæ°4\rsáÛ4e§(Èhß”-Ç*dÅ©µ™x46Š©ôW[q¤pÀ#Í»F<Þm™+—ÕW9\\YŒTº2*85 Ùh#yyò™vÆ †ïpÓwN†ÍäË¬ÇªÒ·»­Ò-ò,ÕZ6­‹\ZÜ)kçY¨8eÞ+®îS»_ð¸]øo‡÷±´±ãï\Zm×â/On=_WR\'²xÁ\rÙ²ÑÑ^l¢aéÆm´aÑ¾é¦\"N­FòéÛ”!¦ò‰T%XªfˆR†rÃIrô.,. ð@­›-+§]î\\¸÷5{oG—hæÐ‘AåõUDö2»Vpì!Î##†¦òŒ¥xÃ6àºkBk“y&lˆ¤Û{¾…6¦Ì©RP¥¯˜ÁÚàøÙCó)üµú,¦%ùübŒ4Ò9ÂVŠÈÞ3HËÐ^\\ñzqË[v0ï¥\'…µo®ÊisW“(ì—«îtEÙchÊ2è‡SÐÞ\Zµ×\\”o,’€\n‡@Z€øjéÙT‰)\\0š‚6lÞVæÎ”ºm¨É¥´™KqIÝ4í)²™kXM·Èš«F÷\0Sml5óø$ÌØÆà^Ù–2Ë»W˜¾ÔÎXõ¾4F3Ô›1¸Ý£ÆZ6§òÓ,µ¦ñÅÂîpP¶¤\Z:Â*?v¸òan¼yS«ŽŠŽ`½È92WX5yr‹ëz±šê^so\0æéÛäÚ\\R1Æ´\"ÝƒèR«H\0«\ZV¦€ ƒ.¶óI³WÌ\n¬&Ó–2Ö”xu½­¼µ-Ã\\c¶µÖ*fé×µm±#C€©R¶áK_Ã5ñ¼>6–9¹YOä¯ÒD¿?8½ÞeÄâ±xâ Ë	ºmÙ¬M^^Hˆ^Ž+­AlÕ’:\0hæ†v·(ÐÆ5êûÿ\0ÕßÁ±·L‘ƒÝ¼ÛÛ\ZÑ^I—³Ž— ”FKÅõB~ªãœ[¶KTi\0y?±s™—J\Z]ü¾…¥¨bóÌPbDW\\@ûÇ]}›M+®31nYsDM(b³Ì²ÎÂÆ‘\rh­ÛbéCžYÌ»{eˆ9¾°«FO‘p£Tñ¾ó¹¦Ÿ¡FK†¦N@&ÍŸBªM¤.ÛèP«n)ðHðÔ\"±ìð<ú	™‡Á^Ûàk¬Šv8P^àÒµÔû6¯?äÜÅ½?Q4ëÍš¯¸Éxn$––`ÙaQ5EJ²á‰›´y1æˆ9¡´½c…ß\rfQÜt_ÂbËƒa••\ZN¡÷apÏØvÃ7N\'˜ÜñPr:„ýUçÉÛ	¤îhº™lÞQé0óyîx&œ£«™Æxi· åìâ‰ˆ©ys˜™µEÉèøkm.ÞiSº{I-µ„‚(Ð–pí„ºLhhÙo.S+‚XèûÔ°ëo,‰m$¼ÊUIÂ•>FÏ‡£,Æ’0¸Ú~RýËàD;9‹72´_{]ì“b0¸sMÅ;qi«ÑÁˆ‰Ôy:ñ¨-¶ß¢¼Yc/f9©âØÇbZÃCÈð—^9˜Žžn\\šË\rŽ¹Y]–×ùIfÞ!ÑÂNÑ òI¨ñvÃ%|ëœ0A\"Ú¯å”Ç<ÜaM¬¯Ðð—Y”DRf3‘ÑðÔ)o\rz7‡5´#[°²fÕNìo¬p\ZþM¥Â¢’¼¦æò–¥ˆY¦¥p”)kÄâsDQB%€ßPÔ+ìaÏ35/‘—D\\ …¯m„ë]ðÕe6œb–M\\âCm4<]OÙ.n±\rÛ©7:>\ZÉž%±	Y²Œ¯Ðÿ\0\rLÊâÈbÂG}í½!â07Ïu‹œÍ‘£•!–W—ÈœMmo„ªÛ1lˆG±Ðð”ÛS²1ìt|5-X‰–Öîà÷jmNžBÊeâÙÙP¨ÑvÛ·hlÖÞPè±¥TJñH©âa|­6ð(¥ŸÜ_g(‡ÈÆR$O´2áù¶~Z‰ÎaQ„K?¤s}@v\Z=ÚonÔŒ…ÄÝÕÕÒhÉûµ3+ÆOˆn\\c/â$Ý¨ÊŽµu92Èò÷´¹ÎÊâÊŸÉXSa#¡á,¶·r:Æ¦d|Ž†¤ZœÁîÔ©jG«¹ÙRºtc$Œš6”-b5˜)kÂ1¤-¿7°¾Ü¾,BìL\0RíŸ7°¸e.øÂv4ûÂ‡Jn\Zê8Ý.¼o÷*é³D‚®cCë–óZå&äR»°¥†ŽŽ‡P³ÁBÙÅ5îxJimÄ\\Ž‡„‚vGÈèøjE¨Ú}“Íì)RÌMÖÜì©tt!°dÑ´¡k1ƒ¢¾…Lµápl>bûvøÔ°ÆòO7°¹K¤&a>Îç†¥v˜SØ<ÞÂ…ZPG²y§¨¥TÙÐÇ ¤‘\rvv”§.k‹,`·Zà§å«Œ™0®ì#ØmŠÍPÚÿ\0)M6Û1¼‘ÍðÖLR¢¥f&òO7°¡T¹u·;*V»š7”:BÓ()0R×‡lV¹àöÙ·Æ¤a\Z]ÂË]6h§«ÑðÔµ ÙèŸv¦ÕI@äži÷jmmÃ¹=îÖk•»…”Ö¤\nRå›\ZÖ50±Þ¥6=ÚÌ›Þ8ˆõz=…ÊbbW!e4·;*‘7[G5s\\,µJÓ-xÓj[`Õhê/¯o“M„žaNæÐØ‰p¹A5º4¿f–­­ÌM.‚unŠ~ZÍÍ¦nArÓ“ƒá,¾%´Ñì Ð¶ß›á-¶S7\\)ÁË­á,¶”qõ:>\Z[i$lw±¹ØQ-ˆOvÒïG°¹Ë¬-±¶Ò›•ÎWŠÜM:òæµ†+JµæFoœú­ÑôÑùaó¾9dfùµ£è,ùa_¤no–€¶YiþÍOËø¥¸ÍÎÖæŽ¢Ï•ŸþÙm´æ·¨Ÿ*¾6§4Ç¦O1½Dùdø¡ö¨h,6rG»[ò³ã†NnÃ[¶|Öõo“iúx\ZiMÁÕKnÖZÆVÆî¢™–ÂË#¶´Üì®s6éŒ-ÆÁ©¹¼¹Ú–Õ’¶êZæëno/KÌÈŒT\nSÉ¼²ÚÑí˜8]ámm 5æ­ o´hù«*YhÂµÕ¡:×UnÖîBüá^&»!Ãê*ÚËEú¼Iâ¶›\r=E´Ëb³»ŒIòv[6¶l6ñz=…›—KQÁ­¹Ù\\æmQ´Æktw–*\"“±ºÛ›ÊK-\nTÍT2šþEÓt¹m‡e(m®™¢ÝÆ×ƒ|ršÚÓh4¯™…vÇ;rË\nWj·£ØKfÖãÉèöî6²Ü/\'¢:Šw.’7Éèv›ŠNÜ0öwQFæíLÜ0öw;)m¤ÌˆêtG¡J©;\"ÖÜÞSj¤­hÔXÔ¡K[ ‚—B¤Ó9-9¤ÆÙ…j‘é\n±”ä£.\04Ø*ÓÐuUîs¦£ÝMÁÔMÍÚ‘¸ t·Ue¶›3uÐê¦æÒVÃ­Ñ…–ÚHØµº#Ð¦Õµ+cKÌ–RF°)¶¶-­¨°e@€Ú*cZ®È‚9X^rY©CèU\Z\"bÑˆÈàÒ£æï-¶moÜ4\0Ú…6Ö{­aÍÞKVÖDc@„²›ÀÒó%”ØFÝSjn–6¢Â™@@A-ª¦5 é ÅÑJP-er\r¤²›†ÐQM¶‹ƒEÚÈmÆn„±šƒ(5AŠ-°º–3t%ŒÐ,@@@@@@@@AŠ Ê¿S†üVs‚Ú–\\3úˆ?¼à²š~¢Äo8-¦\\ü?ˆÝ°”[=ô^ÛvÂT–Íö{CmcY¼ÝPyº¡óuBFªB   æ´h*ÒØG£AA°‹F‚ƒnïF‚ƒqáš4ƒ7muJ™¢ÑPeníT¥¸j€ºµMƒPfˆ3D\n Ê      Òˆ3D\n Ê                                                                                                                                                                                  ÿÙ');;

delete from field_types where type_id in (20,21,22,23);;

update rights set object='forms' where ohject='contact_categories';;

insert into pages (name,description,navgroup,mode) values ('/users/thankyou.php','Order Confirmation Page',2,'no_top');;

select @unsub_id:=page_id from pages where name='/users/unsubscribe.php';
update pages_properties set header='<p><b>You have successfully un-subscribed your email.</b>'
where page_id=@unsub_id;;

insert into pages (name,description,navgroup,mode)
values ('/users/profile2.php','Profile Thankyou Page',2,'check_x');;

alter table email_links
    add campaign_id		mediumint unsigned not null default 0;;
create index i_email_links_campaign		on email_links (campaign_id);;

create table email_reads (
    read_id			mediumint unsigned not null auto_increment,
    email			varchar(255) not null,
    d				datetime not null,

    primary key			(read_id)
) comment='Stats on who and when read emails';;
create index i_email_reads_email		on email_reads (email);;
