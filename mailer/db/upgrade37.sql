ALTER TABLE `link_clicks` ADD `user_ip` VARCHAR(16) NOT NULL DEFAULT '0'

CREATE TABLE `system_email_templates` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `comment` varchar(255) NOT NULL default '',
  `from_name` varchar(255) NOT NULL default '',
  `from_email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
);;;
/*¹19*/
ALTER TABLE `form_fields` CHANGE `name` `name` VARCHAR(255)  NOT NULL
/*¹20*/
ALTER TABLE `email_campaigns` ADD `monitor_links` TINYINT NOT NULL DEFAULT '1';;;

INSERT INTO `system_email_templates` VALUES (1, 'bounce notify', 'Bounce notify: {email}', 'This email is to notify you that the following email bounced\r\nwhen it was sent off from your etools:\r\n\r\n{email}\r\n\r\nPlease check your support desk under\r\ncontact manager -> bounce email manager to see how this email\r\nwas handled.', 'lib/bounce.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` VALUES (2, 'New user', 'New user signed up at mailer', 'See details at this page:\r\nhttp://{WWW_ROOT}/admin/contacts.php?form_id={form_id}&op=edit&id={contact_id}', 'users/thankyou.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` VALUES (3, 'profile modified', '{username} has modified their profile', '{username} has modified their profile.\r\nYou may get details here: http://{WWW_ROOT}/admin/contacts.php?op=edit&id={contact_id}&form_id={form_id}', 'users/profile.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` VALUES (4, 'profile modified2', '{username} has modified their profile', '{username} has modified their profile.\r\nYou may get details here: http://{WWW_ROOT}/admin/contacts.php?op=edit&id={contact_id}&form_id={form_id}', 'users/profile2.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` VALUES (5, 'statistics', 'Statistics from mail campaign', 'The campaign {campaign_name} has finished sending at {end}.\r\n    Below are the statistics.\r\n\r\n    Campaign Name: {campaign_name}\r\n    Campaign ID: {id}\r\n    Interest Group: {intgroups}\r\n    Start Time: {start}\r\n    Finish Time: {end}\r\n    The total recipients: {sent}', 'admin/thread.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` VALUES (6, 'un-subscribed email', '{email} un-subscribed', 'The {email} has un-subscribed\r\nfrom your mailing system. You can review the following link at:\r\n\r\nhttp://{WWW_ROOT}/admin/contacts.php?form_id={form_id}&op=edit&id={contact_id}&user={adm_user}&password={pw}', 'users/unsubscribe.php', '', 'noreply@{WWW_ROOT}');;;
INSERT INTO `system_email_templates` VALUES (7, 'validate email', 'Validate your email', '{f_text}\\n\\nhttp://{WWW_ROOT}/users/validate.php?id={hash}', 'admin/advanced_mgmt.php', '', '{f_from}');;;

alter table config
    add admin_header_align	varchar(32) not null default 'left' after admin_header,
    add admin_footer_align	varchar(32) not null default 'left' after admin_footer;;;

update navlinks set header='ÿØÿà\0JFIF\0\0H\0H\0\0ÿÛ\0C\0ÿÛ\0CÿÀ\0\0L\0ø\0ÿÄ\0\0\0\0\0\0\0\0\0\0\0\0\0\0	
ÿÄ\0I\0\0\0\0\0!Q	\"1±#2AR$ab3BCc‘%4Sq‚’¡³Áğ&)Dƒ²Ñá¢ÿÄ\0\0\0\0\0\0\0\0\0\0\0\0\0ÿÄ\0K\0	\0\0\0\0\0!A1Q\"2aq¡#R‘±ğ3BCSbrÁá$4Ts‚¢ÂÑ£²ñDcƒ’“¤ÒâÿÚ\0\0\0?\0ûïïÿ\0üüĞ>49€qÜ>-ö‰Š±8äãğş”@v0»nmµF#oP+^™Ò©â{÷ÿ\0‡÷üÇÿ\0­V<Gğÿ\0ı~€àW‡áóóØwÛóÑAåâÇ$ü¹f.à&üÌ|?/‹âü>Z´‘5¢£SZd{N}İëJä{wÇĞ=Õë«§ßÒñĞ|}ûôßÒ\0ïé
\0wÇô†€;ãúC@ñı! øşĞ|Hh¾?¤4ßÒ\0ïé
\0wÇô†€;ãúC@ñı! øşĞ|Hh¾?¤4ßÒ\0ïé
\0wÇô†€;ãúCûôr«ÈÀ^;oûÿ\0v€öĞ^eõÿ\0¿ß R¤¨y\'®çİxNU,õ…å°¥qÌ”#Å\"\"ıÃCeO™\0’¯\\cåŠ¢êOán²ûë¬@2¸!P%	¹MŠ]ˆ¼Ø—gÖ‹š\"»*sz]»²MâVsj´7\\Õt§­ÕNì-jRn&9äÄÔ‹(xˆôŒáôœ£”Ø²hÔ‰÷éw.xßÈcù-F0 ÌLGÃkÎÑW~†øĞ!AW½ÈÆı¾o¼ÑÒ/õ“^¶Ñ§˜YjòÊ9EŒÔjåY¢ë1T[¼GqØ@Éœ§\"ñMAıò\'ä¦,ÉŒ(í¸ÿ\0UKP& Í@Äc‘Ìõ…Z€~ÑŠ‰™ÎŠ§hÊm¹øoÀvQüµ„¯oog¸Ê{ÔË3ç~—zòs×¿»,¬m—´Û)êwje‚‘øö6‡0iV@ø<#tVj	:d»ÅˆíE¨ª\\¹ê{œ³¶ÛFÄk\"QõºoçUW¾½¤-\'ií[6§6«¡×ğ.¨^°ğoM1İÌf7xˆª-p©L\\¤SåÄ«YT 6@\'N•E¨€oŞÔ[³û#mí¹`ÂT„ŸHîw7=SzĞ‘íÍ¦²ìôÏªåÍnÿ\0È})WŠæ@©×nµ9¤ë–˜hùØ—é;J±‘Oº‰Tï² Û:~fM¹7Ÿ´Rñdã¬8‰G&›¾273P§å+W%ĞVó/®¬!È¾¿]\0r/¯×@‹ëõĞ\"úıtÈ¾¿]\0r/¯×@‹ëõĞ\"úıtÈ¾¿]\0r/¯×@‹ëõĞ\"úıtÈ¾¿]\0r/¯×@‹ëõĞ\"úıtÈ¾¿]\0r/¯×@‹ëõĞ\"úıt³s\0¬Oö¿ø€Úh‹·*µnáÀ$«‘Añ[5O¿›E1ØıíT½;ûKK›z™}†æ(\'¡×%È0¨û×Ç¼·Û
ÉÒä·\"•«Dİ&İdÒ!:­—n›·šrnÑù:ƒclÒGdÂF~­¹qøßZwoUê#«njÛ+ÒøPô[÷×Ùq‰â¥lûNo=E\',ç&[¤O),Xâ/x:„iîëF:|Í%Œw/Û7):rºÊª_câH†’ü™Élû¬¸Qe¡·”9(÷D£Õ	]i»«%8(Öãm8¦¼³šM(©Z·²{©*4åÓØ™Í? HÀÌ_™>‹…“›J¥%_fU¦ÖpÚ-5L‚ÚE3œ!Unˆ\0¨nàŸGåZÄ–œT˜jµ±kGVŒª*¢\"oâ¿†õ7>M-™æzÌrÂÏ5^çÍêÌxnşØ¶?Ê…]
Çµ°·Jë?>`ûW?.<ƒØ˜„Œ ĞÚR(ª¸®—ù½k¤|‘Ä[!b¬ÒU?VÂÜ¼R..tıŞ”¡—5å9°§°ğ×ë1?¦å3ûÚ—o	)
7ÊÅ_U„‹F’f£#§2ÁÛnëg`ø¥ÙR(Aˆ€—´Q%ùj˜å0š­ˆŠÆ¦Tª«Óş^Tö’ì¯$ŒŞPmß]i•~5){Úìú¾æ,ÁYÉxR“Ç·â#!\"öDÑÑĞò‘·‹·?Ung/&…Y¸€ğpšeâ~÷Ã/ì.ŞÀ³lØÒó.«`¢Q©
U4¥ßÔ½äU¶[#5??,²]l]è©Š‰®´ë,S£ü;ÓFÆS÷Å/j4“’›IØ3Ì }æ§‰V†Ys¯ºÄ‚jn<<õÀm]»hí^S_’¦ì<LMçaÃ÷îÎY,),FÇË¥s^ßïBSr6ÿ\0õ×8nÎy×é Fõúh‘½~š\0äo_¦€9›×ş\0äo_¦€9×@Ç×é Fõúh‘½~š\0äo_¦€9›şöĞ#zı4ÈŞ¿M\0r7¯Ó@ëôĞ3zı4ÈŞ¿M\0r7¯Ó@ëôĞ,Ì\"å?öÿ\0ø€ŞèhŸ~<”PÀ±ÊcnJ{\0	¼tC‰0Æ1¸ä¨Z•B¥9gxânßoQ6è¸pñTûî^<“rc¦€ÉB·1@)ı-ƒv¶¥·jÀäØ®r&æïİ]*•ßówZŸ5X’ñ0Ñ«ë|!QİaõÑS;b)·óRŒ!.©·œËÕE[ÄÃÂD¿L©””’
â.¦ÛÄ,=¤•¹Tp4‘²VÓØ6dY†\'M9–©uÛúYä™Õ8ÎÕÛ›=mÏB„÷\'3¥}ÿ\0áñÍÊ[Î(Ã˜ÃUÓªã
”5R©ÎUÌÀŠô¢í¾é\'S.LgÔÔVp±LtÏøü¼â«Whm‹r=ù¨î{´„Ô¹¿‚ó³îĞ“$¬Y)>d&îéV™n]5Èªé¿dµFÛÔ-ÎÎ½áö‘‘BÄÊ‘XMnÈK$/_Aê¡Û`Ì‹}ãUH›:!à»ß¿©]U\'¤¬
:òÚ\"ÿ\0‘!RµÒşµ#öufÏÛJ‰}Ü*øâ\'¸Œİoõœ0eÏIøí³Œ-ˆé°ìÚ×½;0òËr©»k³YµoŒWH§ı\"€&¸ûXßŒ:MˆÙË#h%ÙiDT›œ‰Z¢¢Cd,«K©z´ëÌĞm…¿jX³±$¡Ş‡”ºäÎÿ\0uÕ-CÙåÔ‹Ş¡0$z¶_­ó(>ÚõÊ*iA(éòBŞ¸@¨™qP¢_CŸ½âX£Ê…-c[‘°—ÑÅáóWÛŸrë¡$l=±1lYpšüœí÷·û=äîïmÄÆPJMÛPÆG—y?%¯ƒbÿ\0X)óíóœ~ZãS#¯TTS·0ÜŠÌ¢br\'8s(“‰„ÜÃĞCo@Eõúùè‘}tÈ¾º\0ä_]\0r
ö7Ã·˜”ß‹o]‹ÿ\0Ş€ó\"ä8	€w!~b!ÀL)6æ˜‰vóáóæUv!Ê]÷Ğœ‹ùÿ\0¿@sş¿—–Æ—‘‡ğ1Ûa úï :ó.ÿ\0»ş¾šeõĞ õÿ\0±Ğ¡Jb”ûNn)ÀS¹	@¾ÛŸ˜€ôï ;·0ÌÜÀ›Û‰T:_¦âşğh ²cóU€	Ê
”æOã”î»‰{g\"œøñ0ì:¿2úúşCùh ¦şe!ÍÈ>ìÎÀÿ\0Øhgr1ì¿’~ù«ø¦ë<–|ù`hÎ-£t|JÎd©ğ¢TÓJsÀª	€\0»è;™‚rµÉ˜»R‡:%“„g*ÀWI^Ò©x–g8|?1úh
Ç2úıt<ƒ@fG˜â Çÿ\0(t›@ y¯ü@GNªğ‘:ŠÂ7<l“õ˜N?`UÇ(¹UP±F‰fÅÈÀ¢İÈ‡m`6à`ò
o6zÓ…dZ©0èx­Öºírõ®»_²j-Û2%¯#„Ø«Ş½Ûÿ\0Ëy¿æöî>H+¸—%Ü/.1…Z•7?xe\"ê%İq£%Wqõš¾
@ò{€$Ô¨ˆò\\Ë¨€|j úúŞfØ””³±^øL—§¬Šş¯Gù÷Ÿ.ÊY£]¹÷¬­_uãóïT]^ôÃ€p¶>’%I;4½yíz[0ÂM£lxÚN´ëÀ!M9 FÉË4f)xå”UØ;r~(Å/taëf¶Wh¶†,xwšÖzD–|#à}r9_—ÜººæJŞÑÛ–E½\'EbÖå>Í3öã¡®§ñ7P¦ŸÈö‰YŠ®WxŒ6G±I½’zÑÛåD°–i7”PÛ5[áSŸÒ&ØuÖm¾ÆY¶‰$à5«¢‹Ì½™d”íşÜæÈíLÌ­µ+¨ş“•:úó,ÿ\09ZúCÍ¹Òua
ïdc$á:ü“éPB…iÓã¢¸¹¦‰x2v´~Â½×üz‹ìi{zÇ³bÄ³èbnÊêÃ]usV¹¦êåY×bÚ”8SÌH1™ÒKØ—üOsßîø×¦.Ÿm™&\'ã:lµª2ºİ“×cÙC1fÏÁS*®Ü”‰$ryTí°òm›Bba[2ûñsUÓ»DøâwR$% \\–b1=d_Èª.›zH¶uï@ÿ\0
°rÆUró(ºy%ŒñÕ
Ø¥B£)èIªÂë\"¦PUÊª r“b)ûP¾ÛqÇĞÌ!å±ÓoYogöf¾Ø2uR^¨½û§‹Å­À¿³/Œ_½Oı÷˜®O”‘wÜ¿`‡oïÇ²ù²u³ÒÅJ
Ée±ç4\\MRá!çáómq‰/øÖ(dR3µ–mäeÅT\"d7=Ãm‘!ÖK1x¶/4»Î”\"cI§«FEY(E=ã(
£ˆ¦1	€¼;¤ùÔ@P*„DH®ßÚËÆ9³fjGò‹ï0JA|@+;
ïÄ¦ÑVÉ‘c£$ß`Q™Ä‚e8;*Ùìúş$“°uñÑı^£\\¼ÍgºB«`È¦«>”}\"ŒS³3‘tœ,b+;\"	(š‰
« ‘;Åíï·€‘ÍC)Óc­Ôm’¥cf`‡±ÖWMÒ.HS
jLL%Q3¦ªj¤`(‘@!wæà*övÒ±ş2Á7\'®¦%:–¨¼Èö‹ûå‹›U·	´j´¤ ¥(ñÇ¢—/Õ%Ie•‘$8ì ;´¹úLÉ®,¢gj5ôıÚ¬c!ã8U—íĞñkµ‚•:@ÙÙ¨ºPßƒcğß},^Õ|O	Ö,f5ã7Á1ô©YKNQ]Y¤ÒÈ
Ş8hZ²ndRí
·›—.<Cm=/}StûŒ¨u<™}ËtêÅ&ù„ÅfFH;–øåÚ\' Ek‘H”ïˆ\"ªG1[6WˆaÌ@ÊÂİL`Î¢c¤äğ¶N­_Ñƒ;bÎ6‰tte¢¼Vı…^B?*/JExŸ°ao²ÿ\0
ùù…eê£§ºsŒ˜ÒÓ–jpN°ñ ÿ\0”d$_v«/fkã+ÌäPÛŸqÒeHª“âÛÏ@|õ{mò©­ÖŞ˜åqå¡óºµŸ>ÉPîëÎd™~Ìí9ÉÕÚ…µl±È\"˜ñ\0ÛÈG@]ş\0ê~\"ÓĞ½3©‹Ÿ”}aº©ŞL‡q=b2¶6\"Û}·l8ˆ˜T&À<€t{2¥Şã×­’rå?atï\"ãl‘4(>xüY¹s)ck<äÄl²›dÖ\"G\'/yì\0}æ¾¾:IéâÊµ7/fx*½±£v®×ZÄZ­2±¥zÏŞÆU½B=øµ)Ñ2gäã´yä[ˆş+Ì¸§¨š\"÷,E|¹ÕehãËÁª»w±‘GÅ8né›â$³E!æ™T!{ƒğîo )_¦ŒO†±§DŞÑÉ\\7ÕŞ¤ØÚğÖLZYÒ•	ÚJ5GqØÂñNÊœ™Ã§ææõÊj´*¾œKáH{&CtÆV^E¤dteó$/+)(èˆ5e¡œªåëõ?›E¦±ÌaşH`¼â\0HÈi—CS×„1ägQ5%ln_¥ÙG¶¶U‡—áÙM½åÌyaULıÔ6p“ó¢äû‡\'/ Ü³ÔvÁsT
şYÈ™\\£&¼M9dŞƒI§Ü3l¡ÆA‚Ï›wUYB2)Ëå :ôóÕ&
êVFÖL\'~o|.?‘ok]”-5œ{éË¡P{8Ñª.È~Ê¡ÜfuÊ_‹m÷Ğ·@6€}ü¸}öØvø½JC€ñåûbzj›íE+†ÇDÜ1™·©CÓÔg2­œJ‹¦¡£+-;O­“ÂŠ¼¸ÅÀ&rª <¢¢‚)ÿ\0\\ª_-o,-™¶mõı÷¹”×‚û”ÑÚÖı—b§§ŠÔøí>xó÷´å$mL0½j;Õí¯Í#g”®·nG¾,»o
áıºĞT“8	æ“dHŸÅñ:UŞ¾…±¼ŸHI2³Q_9´¢ÓR”JA½?Ÿ-ÈA¶ÆÛZñï@‡WüMıÌ§±tı*Ú«Ù.}é\'&M²Œ†È½¿Z\'ª“JN_¯!Á%œÈ¨`9Ë7)¾İRıÑ7@RPÜƒ\'i ÌHLÃ´`µ+	0İ-
·$º¯É_KËV§En;Z\'8ÇÙ©·MÁ‹#õ¿Ñ˜>æ©è×
_Oa¹Î—úWñ«2‰ê¯8F¦-Ojš@èá
KñL¡¢ş#M)ÛøL¢ê\"SÆ(ü:±whö©X‘Ü²js˜ÚÄtJ¦ª‹	¾œÛÜQPºø¶
‰~ãysŸÑuìUrÖ©àD¬‘“òwP7Äì·Ù—×K„ÂÈÁE‡‚lD‘)Ü‘üD[bº(Â R‘.@ 
¼¼øk¯³övËÂcQ°sÓñãU9™‰ÙûZw%bFí¥=úSMÑë\'¾ÄÉL‡Š\"…Ë™j¥3¯cršîŒ²Ë“¡ç®K ©ÎsvHR¼—ƒt	¾çòùÜŸe¯j¬Ãaà¶¹CE½•ç]muZÑ8QXò/³$ßŠï][sÂ®÷“G¡É™.:`qvæh†
ÆŒÔ3rì‰¤â*èCO¶Xéó ªÓp[w¹ËÏmkM‘^]C¨YomFÌàÃ”¼}dpĞ8«,.®’ä¢.E\"ñ7p®[“p
m\0•öyá¼a~êãÚKr»Ò«×	ZÇR—Šõu;<j±ğ­§2¡y—‘¬d\0é¢åÇ…n‰×!@ş.Îş{è
_³Ÿ\0âõíŠ{Eƒ–ƒÃ™X°XÚ½6É¼¼
J2ù;?ïÂDD¿!ÑMqF9±pñ$A3ûÁÎörÃÇã±ı£’¤ÕÌ}~ªÍ@W.Ì`ÖšVLÎ‘ŒCù¤“íœø‘0A»bW¸€Ù{p¹ôÇr¼İ±íVí`°äk1w(VDZU\"¢X.•~5´™›vê¹t«§\0‰HeW0˜LÜö@\'öj3«ü_³£Ó1¯Sv¸j|{×*<4tyY’mû‹yvÄŒÒ:¤)H
9æãróá #ÿ\0³ÛÚsw²o;bz<š0Ûµ×!E@=UeY6QÉa œ/áÃR™BòH²ÊDÉ÷ÅOlà>=æ,W‰ºWÌ]:Cô÷Õ^/‚mWui¦Ä¤úAeàå,ôÛ¹QHŞ6`‰.«†ÄPJ¹Ö\"1Î]ˆô^Ç‰±cŸl}j¦ãã÷Uinš]Ï¿­™¬ŒûÉ)V±¸Š:%DÎÿ\0\0w8€ü»Ì	µÕeé‡Wò i–¯î|TNÃtú5¾ÒšJ3J5rÕ ×àŞ5¯e4“MÂ% )ÛTP*¼4iá9Éf×*;´zzÒÂyCØŞÙ1ÒÓ5çjfÍ)µº-V*M›ÚÑ$Mvü9¼¢§ø¤²ã¼íí’ê/)A5·Ôé4Li~J›0˜¹­ÎÚšcŠœ,#ÛG\"¶t›$ß82Hª€N}ù@húÄÇTûçµ?¤öz%†<ÿ\0§5RÀ×£ĞdÚ2
fW–Ùº ƒd3GÃ%Ú×ò>`Wv~³aî‡ú‘èj\\Ë†EK¨ø\\lK~è»÷÷ovbÁ@Á$ED ¯¿ÑƒŠ€`\'O¸½§NÕü‰®Vê·éŠ¹^˜\'l3ÍpâëYjPópù‹‡B;ˆVíüb˜œÀ?=Uu?Ó+>¥¯˜ãt?ÖGQîØµÉRf€’i¤lƒğçzò:ic
š/UnÙ’)*N$U]ü€iı†e!²gY5bİU£XÌÔ\\¯JvíYAªÍ1—ŸŠrÌ¹
‡n‘ ¢ÇOïû qI=ø”“Ù¨?ùt{IJ<vs“L¥ó Aÿ\0_Å É*\'±>6ã–!’º‘‘Çó+²äEU®¹e!hxÄTLwâáH”Z.]Ã¸İS&\"è
5ÖyÈı(R°õÙY}©Ùá£«’‘=CF¹”˜²Î¼Q$ÜX§fÈÆ®ÉGÈË“¾c6RHQk»NÏÁ‡x÷´>½|±`¯eU/(#)\\¿;«ŞhÖt¦Ú™9–R
ŸÒ ÄšN@Àªı²‘U	”0üÊ?€úRéË§,+ÓlSZ®£FSÙ.Ñ«y‡m\0V–±+İoòzM}Õr¡{ªˆ	Ç÷h	U )ûÚÕ&séÎ&-5¢8[>Á³‘,›H‰T÷,|YJT›­·ŸŒ~°û-IOvnÈµáVja>‡¾*ôİL¹¤{·vı­d¥ea¹aêûÊ”î»øŸ7Ë]ñ8şÏr–³NÈªA})8åG¯ÖíïÛ(91¹ôHˆ¤‚~}„×Òr–t	(×[
°SG3³‡ò	š™´ÿ\0ZU~btÄ(‚Ü»d\00­Ïqí*.JwGÉ/ãPé¥¿ôõ”Ø1\\¼;ü<h)½|	G€:=ÏF½åHª,Â²W÷…æÌG0TöBT{bb÷\\›å»v	¹_øCç®Si6¿gl,£¹\";ê¡ª/ótC¦±¶jØµ¿Vkš¼^ÕAÔ†´tß•\'1}¥e_«İ£øiÂ6+f“°²Eî0”jÔN±aü+šİ“&¸s0¥ñl6[he¶B[¨Å}QÌG^¹JëuµáÑo°Â·l(¶ìXW•èŞ‹Õ.âw^ZeÖ¼:ÉgìÀÁ†Ê½C·¸Ë43ªÎ#b[cÍËÉ¢ö§‡U*³ˆùAr9vo#ìT/¾\'ÈùK·ÎÙËŒ¦*ı¬ÿ\0¾ã¦òwcyÆÕ¾ş‡aô¡{£WrE\"Õ-ŒıáW¹×\'jó¬ÀÀEŒ°2Qƒ°EQàr‘Aàm‡c€_1ŸB”ù†ê^Ğ>ƒc&ğm#²êó´™‘uˆ-‘Ù»¥ªH?tw£eg6ƒ¡9
¢Š6Ê†Æÿ\0/.Ş`>ô•˜as^Hë#ªÇ0êçÌ”×ìın‘Z_Ş5üYWO‚W
Œªfp-ÛµE4Ò	pp~áüQŠ@6tı–°Şië¾Û‘êÃ^¯æ®¡æ®øİøËÁI‚¶âÓa–+ßåePÓô>Ó\'Ï@ôû–±/RşĞ‡jŞà¨f¼£U±ãya™’	ø¨ù+bî—Q¼C—
´ƒöşO‡ãÿ\0Fà+tù–ñ¿Y}oekSİ4LÃ5Py¦K3 ¥Ÿâ„±QVxİßëÛ§òĞ²³§ìµÓwMó3Õ~Ç[ÔÉö›q>ùç!‹U¼MqÓ´·0¤o.Z\0ö|ô÷–°u“«×ùF®Z»l¡Ô
†ùIPÓp2£-ZxõÚ­dÔBË•[É*
~Ôš&
€¼=[\\ò+Xo~„p·Tİ\'ô=u«&uÛŞ¬–š†:›¹U†>y”£8†nJ(G\"a\\l+.Q-‡B)‹º¾ë7©~™²N^éº#¦JM¶Õ.¥ŸdZış×t|İû)Ÿ³ñï ‘mÛhéF ™›¬ÅTÙaßaqÕ6ê_õ·ºÊÀX‘,ñŒÆ7šW8j„¢.ˆêAB¹FZq3i‰]¶6È´qüÆÜ„
ğúÔWî*»Ö†Søª†½ItÉ×.Ãñ·ëe#£KÉØ)íÊ
ƒúùfIªøĞ–7›à¼Ó„íû‹{½#¬‰û Dh¾Şî:U
?ÂfŞı xs«¼Ù‰ éÔ1Í¾’­J
óU±ºÄñ^å›%z&Ç\".­*ùã¹UmM÷Ùöæ*õ)]Ş´,MÓö[¬{Lº¤ên«à1JÆ4jå&Øi˜FjZ*¹TbéÁ·ry$€ª°rQ2ÍƒUĞF\\úcÍ–?k&,êT”Î>‹×oNÁTæy5é–Â zÒ‹.—“$
üË¹?	¾U^›’¡`r³2ñqö›Öz„€ˆS¿´Õ²uº@fcHéMn8]¸‰R¾¢€é`} ÅıÉJ>òt&\"\"9S<SLÇË\'tËœ¤½«X\'©ºÅ!´¾¬ãÕa-·°ÕÙ!ëÊÕ¦º¨AâR†ãïónƒ~`_/Åª#­0õZZK§½zJë7¨\\ÏÓN#ªæª÷P®-’qsS3°±Æ¯9¸Ø¾Ö“Å$êB1ú>ìu÷$
Á›„ƒcöÿ\0«Ïv¼5)ªqÏeÏJ=Nôí–:‘´õUo®Tm[’·³³V¦XY\'É)%-8`méG€€o±x˜œ67>E¡\"6œ;}ÁU;{D=u-‡z/ëodlmö{ æeâ×BÙI•5…ô¶/u]jŸ½\"$WdßöµJë9\'ïÕ`ßb?g^K¹û4æºYË1Lñ¦Wo\'¯ÕxJÄØXDÏ&è®a>qUré27x¹duŠs`qİUãÚ=.uÅs[}²8kDàHÎ™¨¹
lc*½3(KÚ İ$Â½†÷‰°¤›¯
O…ª£Ú:3¦¶ª­íé‡©>¢ì}Ù©¸ıµKÊKËæc-ˆ8Ú›É‡”×‹™š–&F_Î=ù~ä§ß°#å¿M™BE¸ş}éş`ß€]hÏU€º@KU­p‘–ìëqm/	*ÑQÒö¸vÜ pó
ş-÷åü_®ÉÌÍÈÇÄ†õGñ,ÌKKM¥ØŒG3ÔüûOŸ.£}”y
øÍNŸ\'g¢Y_v!\"‹	
#¡/tU’rüÿ\0´°áçâÑ —Áá¿=O{=å>V4µÉÌœ›ÜU¯cUFŞòwhÁ_ÑÕb§İ»îU÷oBeôßì¥ÅØÛİö<Èõ¹o@Ş8 ÊW	cÈ§;y¦£İ‘?ïTmå¿„×!´>Sm[J¼¼•?y‰ı,ñü¶ÈòyfÉ7Ó;”/îğÿ\0©éî-QŒk8¶m££Y·bˆ7bÂ=Z3dˆm÷M$P\"Eòşi\"‘ìµ½ïˆ·œªø»Õ]áù’<&ËÂİ
îóJÄö¤ôàO
·Êµèå^1*N_,V¨wIÑİ¸ïØãÇ‰€Dè˜<BGØ‡]?}Ü$äÓhfZÑ%âg\"ó_zíÜ¸QkDªo#ß(6OÈÃŒÌÎ“nÖòv÷×pêû9°ap¯M5•e#ü%Ã#·Û((—iÈ# Ô¤€Wqø
·—Dÿ\0Å¾µ>P-Ç[åòI¥UÉÖl6ÆóU•}İ=İ/½xĞ\\
óÿ\0óoÌ5Åšæ§NÎæ q0în`Cü^ cyíü\"OáĞğ; \0\0	—ÈSùù”Å\'Ì§O~>~¤àoß Lß2Ãøwà.ØìO¸\0âR‡ÇËä#ùì\0£ò!÷ø“ŠbD1:\\0r†ûb	ë Ñ¶Û~A¹ùÄ@b†åH¥ùÌÂoÅ LÂ^;;bPàÂ),
àâ>@ÛïıÚ!•/7ZĞ²VS·/l²GT
”N¸•6
	T+¥àãÂÔª%+—ƒNGK(·Øü>>¶bÇ…v½¯Œ’ù*Ö˜ÉÃìêºœÌµ«*Õßf£7–®Æy:4á\"±àA›\'É6šˆPµØ‘`d÷F”+6ª‘ä€·Á²ÀvF‘P ‰° X+–—ÿ\0WæW™/ÎæS¿32=²Ø2¿Åù	´º’•z»DØbI§c4Lª­|éÙêŒË$×Û~ÍÙ‘p˜ª^É?œg/gäôñß=_~ÏÁei1Zrzú=f¨²éÓúO
*cyş2ıü|ñ>§ªât¼Åç\'¼aèÙf°¢„©8•ÇÓvbÉ4¸&8¸˜‘/×1L\"(+ƒ¢I89w)ˆÏ·Í0W¼–,­ø“«/’DÎˆ»–ìò\\”Î|û[%š³·»†•PvRÛ.SN™ÉÍS±Èò4Zü!\\ÈJÂãË-o½´º‘ø+.yGˆ(ŠBCÆ¨G;	Ç³¬ÿ\01Ëò8Pò¼õ£«Š´¢*ÕMo“ÕZ}ïÈWÉõêiq÷”,5œ³„]=ÜEy²_á)µÈ˜k9 
	¿Mª}Â¿tê•Ã¶ı±Æ[?WË`îÖ•®ôÓNòò[²ÿ\0ùóô“kCuü›Êÿ\0(®æPŠÒ6ácÈÁõ6Jç<K	1YºäˆHÿ\0v* »slä§`ô¸©…ºıÕéV¥3¢o‡‡‡é=[Ù{iø+~B’±ôí#—¨R®êª%_ä˜ÿ\0&¹cVq>zì²2=ÄJQT©ì~Ççåb’ËÛ<‰Èv.
îµÉ¯î&TÄÛ#XÜµZÌ[½^­jÚÉ–*£F0fŠœÊV¦ÔD²MA«UÁQnBİ“0S´›·¯ÑÙ˜1)Sø3ƒ÷‘½ÄÄÕ­œéß±é°S+ÙîGi–î;ó^6Yh6N•çÖ/ïÏ.Ã\'å_
ƒQæ§„ÊRQôQ+<úğCÜc¬„âuÕ¸	JÜ¿ŸŸáÕÙ21¯\"z+Jé¿M{z\'§Qph·q—¨ÍÊ¹XÔœSw^
Ä.òUg)cI	8’%0³”ÙóQì”0Câğ[ÛvXö}Ëm„ÍÌ\\,¨îÅŞ‹ñÜ_œ›d´+è·ü8v‰<kšç”½;Æ·$d——wrh•§â\"ê¶öñ‹cÕo£%`‰‹QVë59Ù:I[š ‚ãÚ/kó­Èã2írDj-S†ÿ\0ì‰L;]°ç0•µDùõ§…z›ÚoQqW5˜ Ú¼´MQ’ºGšjb%£éö²‘Ü@%QO´ .±V!’1UûHQ|Y­Ÿ™–Ş·¨º&zh¿‰~¹2ü2ã¿ÀÒBu±aêşóªÌM7%G\0OÚ-Éº‚#Uó«Á‡Š‘F%Ôß‹¯5“ñ\'É¯Šùêóì¹:i_Ò(”ı’¸ÙÖ¼ÄÏw°¥mæı_Ô/Këëötñî:Fu][	¥á)3öö°“H ûÎ%CÍ5‡ræR-ŸÅ øŸ^à­»&îì÷ìq6ZbN4O½RÛ6‰¯ú.??ÿ\0Ÿ…Ü.šçf¥±üaEææ¶4îQ+ÔûÇ­öj»cMBZ_¨¿»«ø3÷şdìØôÜüàı—ŠåM{ŒÔµ¡ªAæü¯_Gº‰ş„‹ ƒäCüççı‘µ£ÔÛŠı\0à_MÓ¶ğØÜŠ%*e0‡.ïP
Ã¹ç±xƒoÏ^\\†»ÓÄ»©’§°ç¶||¼ö1 ıE˜?ÑÈuKš®(W\"èvâ«MÅ&;†M^ »Wˆ&é£”Œƒ–Ëª á#¥Ù:K¤o#Åò1GçëªàÅ‰-ö­‰ãÙ
.OJ§Ø‰$‘IˆŠI„I$ÊM.Ñù¢DÊ°`R~EòÕs³rŞ^\"ë[ÑJ\'¡éÏú}tÄ¾Ÿ]\0q/§×@KéõĞú}tÄ¾Ÿ]\0>.¤“)† ãO›4éåcFO¾éK$¢²ó*„™VD2«*&øVøGòfyÂs•C{ÒÂÁº´ú”÷;ÀÃä2Ü
ïyƒ;‡±õ’síÄ×•X!ÂPÌØcâg¾Î­â >ÑW˜¼#	r§ÚñÍœq);`%Lx‡°m	¨)“º\"‰–sª.«ZğâSÏ—§aìÇĞ£F4YÁ#é±÷¤ê,•\'ï‹¡Ştn~5ÏŞş^w~5ë§ã¹7ıF¿²ş¯ÿ\0o¬¯‘Âêÿ\0xÓë×úhl•Çtå¨‹Õ„nµjd¢šu]ªÙJ™#Fİ
+Ü„¢×Šf?t\'/p¢;jß,™åxèêDõƒä >O€k„ñÛ0±ì\"ä¢“¯U~ÄÃ¹ƒ´Û`¥V†U”ÚÍ/ù\'Bº®˜7]gÂ°¾QNà™ÀÄº¸ëBeë«ÓÍ5»TT_ß’è[ól§ªy%‚qZQä‹±²
õ¢°¡ÉËÉ(â6ç>®ÎwN$UEœ¼“L¯”x±”tEÊC ª<CW¼óhzÿ\0M‹øSÙ–”Ï#Ï5Hz†T6ÇRqólâ_º›œVÆ„Üİ–ÑbšVaZêµO#/<õÃ—œcÖ3BÚ«¤D@ 	÷ET£Î“K
òáú¾Ïíñ‘ï›¥qñ.¥ÿ\0Xİ¡ª-(?ÉƒX¢§Gêõ1„3É%?ğû¦JG¸‹,ˆ­â»FINkÛâõk—GYÌuZÅÆÅ¿ß’SŠqâ\\äpy\'§¢ÁÁ»ı_—‰¨³áÌ}o3MÄ<9ã`U«\"¤]‚Ã]UÕaÇoÄVæ¯:kãã•íºÁçy©øt¿œîû-hMK§IWÒâ÷÷Ôò<Œ¼jeºægZq}:Üa6rKKr£º±ë–K
9Ì:ÊC«\0b·yOrÁnS$R÷\0
- ÚP±yÕH»ş5)gËÅÂÊ˜]õşÆtÅµ`¥¾ÇóMä$«2Ë@I·y98¼ƒèµRív—QÀ¾çßñ=ïí5jäÔ)¼Tu\"âÒ[«Û­;‹’—|+ªŞñü‚c.‹UúríçR°¸¼^¸¥$Ò)ÄCìy{‚I³tå¤G¥.ÎëYIjLîUæî»§Sã´°Ë.Y‰»>\'øA¿îøi@mSZ:±òãv”†®¦½mZŠ‹ÂAÊH¬Íºæ]v¦rš\0ìHáĞø€QÒç:bÖ˜ùÛéU¥Uiº»·@²åàõ™¨á,dŞ<ñIÖ¶`¤f8†Q¿½¦ş(ÜM%ïj+aSÄsıŒÃËöŸıWw^yÎkÖ_§ÿ\0ÊùúÊüÛ+êşÏşşŞ\0Ï
ĞcRšm•²ŒñÜ(î2\"ä8¨¦j9–Jdç¯E2”#h‘ï¤`‰I‰
.N<Ö´üEç¬7öÃMQ3ªª®{ë\\ºÖ´6Ê”Jå¿âÆ;l–ºŠq³~
¬ì$¢¢Ïu¼+¤ÀZÏy÷ôÄJòFo ÿ\0ß÷—Œzšëø°îƒä%¥3ésù_
|©ï›`z/ø^#ÓPhÿ\0îËÇZób)´_@\0Ğ€4 
\0h@\0Ğ€4 
\0h@\0Ğ€4 
\0h@\0Ğ€4S/ò”ÿ\0Ûÿ\0–:} ?ÿÙ';;;

update pages_properties set background='#cccccc';;;
alter table navlinks
    add logo_bg		varchar(32) not null default '#ffffff' after left_img,
    add footer_text	text not null after logo_bg;;;
#update navlinks set footer_text='
#          <span class="BlueColor"><b>Omnistar Interactive</b></span><br>
#            6525 Belcrest Road, Suite 526<br>
#          Hyattsville, MD 20782<br>
#            <p class="VerdanaSmall">© Copyright 2005 Omnistar Interactive / All rights reserved<br>
#              <a href="#" class="VerdanaSmall">Legal Notice</a> | <a href="#" class="VerdanaSmall">Privacy</a><br>
#          </p>';;;
alter table pages_properties
    add subtitle	text not null after header;;;

alter table navlinks
    drop topnav_color,
    drop topnav_textcolor,
    drop topleft_img,
    drop left_img,
    drop link1,
    drop url1,
    drop link2,
    drop url2,
    drop link3,
    drop url3,
    drop link4,
    drop url4,
    drop link5,
    drop url5,
    drop link6,
    drop url6,
    drop link7,
    drop url7;;;
