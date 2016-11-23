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
/*�19*/
ALTER TABLE `form_fields` CHANGE `name` `name` VARCHAR(255)  NOT NULL
/*�20*/
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

update navlinks set header='����\0JFIF\0\0H\0H\0\0��\0C\0��\0C��\0\0L\0�\0��\0\0\0\0\0\0\0\0\0\0\0\0\0\0	
��\0I\0\0\0\0\0!Q	\"1�#2AR$ab3BCc�%4Sq�������&)D������\0\0\0\0\0\0\0\0\0\0\0\0\0��\0K\0	\0\0\0\0\0!A1Q\"2aq��#R���3BCSbr��$4Ts���ѣ��Dc��������\0\0\0?\0����\0���>49�q�>-����8�����@v0�nm�F#oP+^�ҩ�{��\0�����\0�V<G��\0�~��W�����w���A���$���f.�&���|?/���>Z��5��SZd{N}��J�{w��=�뫧�����|}����\0��
\0w��;��C@��!�����|Hh�?�4��\0��
\0w��;��C@��!�����|Hh�?�4��\0��
\0w��;��C��r���^;o��\0v���^e��\0�ߠR��y\'���xNU,��尥q̔#�\"\"��CeO�\0��\\c垊��O�n����@2��!P%	�M�]��ؗg֋�\"�*sz]��M�Vsj�7\\�t���N�-jRn&9��ԋ(x�������زhԉ��w.x���c�-F0 �LG�k��W~���!AW�����o���/��^��ѧ�Yj��9E��j�Y��1T[�Gq�@ɜ�\"�MA��\'�,Ɍ(��\0UKP&��@�c����Z�~ъ������h�m��o�vQ����oog��{��3�~��z�s׿�,�m���)�wj�e����6�0iV@�<#tVj	:d�ň�E��\\��{����F�k\"Q��o�UW���-\'i�[6�6����.�^��oM1�́f7x��-p�L\\�S�īYT 6@\'N�E��o��[��#m��`�T��H�w7=SzБ�ͦ���Ϫ��n�\0�})W��@��n�9�떘h�ؗ�;J��O��Tﲠ�:~fM�7���R�d�8�G&��273P��+W%�V�/��!Ⱦ�]\0r/��@����\"��tȾ�]\0r/��@����\"��tȾ�]\0r/��@����\"��tȾ�]\0r/��@����\"��tȾ�]\0r/��@����\"��t�s\0�O������h��*�n��$��A�[5O��E1���T�;�KK�z�}��(\'��%�0���Ǽ��
���\"��D�&�d�!:��n���rn��:�cl�Gd�F~��q��ZwoU�#�nj�+��P�[���q��l�No=E\'�,�&[�O�),X�/x:��i��F:|�%�w/�7):r�ʪ_c�H�����l���Qe���9(�D�՞	]i��%8(��m8��������M(�Z��{�*4��ؙ�?�H��_�>����J�%_fU��p�-5L�ڐE3�!Un�\0�n��G�ZĖ�T�j��kGV��*�\"o⿆�7>M-��z�r��5^����xn�ض?ʅ]�
�����J�?>`�W?.<�ؘ�� ��R(������k�|��[!b��U?V�ܼR..t�ޔ��5�9������1?��3�ڗo	)
7��_U��F��f��#�2��n�g`���R(A����Q%�j��0����ƦT����^T���$��P�m�]i�~5){�����,�Y�xR�Ƿ�#!\"�D���򑍷��?Ung/&�Y���p�e�~��/�.���l���.�`�Q�
U4��Խ�U�[#5??,�]l]詊����,S��;�F��S��/j4���I�3� }槉V�Ys����jn<<��m]�h�^S_���<LM�a����Y,),F�˥s^��BSr6�\0��8n�y��F��h��~�\0�o_��9���\0�o_��9�@���F��h��~�\0�o_��9����#z�4�޿M\0r7��@����3z�4�޿M\0r7��@����,�\"�?��\0����h�~<�P���cnJ{\0	�tC�0�1���Z�B�9gx�n�oQ6�p�T��^<�rc����B�1@)�-�v���j��خr&���]*���wZ�5X��0ѫ�|!Q�a�яS;b)��R�!.�����E[���D�L����
�.���,=���Tp4��V��6dY�\'M9���u��Y䝙�8��ۛ=m�B��\'3�}�\0����[�(Ø�UӪ�
�5R��U��������\'S.Lg��Vp�Lt�����Whm�r=���{��Թ����Г$�Y)>d&��V�n]5Ȫ�d�F��-�ν����B�ʑXMn�K$/_A��`̋}�UH��:!�߿�]�U\'��
:��\"�\0�!R����#�uf��J���}�*��\'���o��0e��I��-������;0��r��k�Y�o�WH��\"�&���Xߌ:M���#h%�iDT���Z��Cd,�K�z����m��jX��$�އ�����\0u��-C��ԋޡ0$z�_��(�>���*�i�A�(��B�޸@��qP�_C���X���-c[�������W۟r�$l=�1lYp��������=���m��PJM�P�G�y?%���b�\0X)���~Z�S#�TTS�0���̢br\'8s(��������Co@E�����}tȾ�\0�_]\0r
�7÷��ߋo]��\0ހ�\"�8	�w!~b!�L)6昉v����Uv!�]������\0�@s���������1�a ��:�.�\0�����e�� ��\0���Jb���Nn)��S�	@�۟����;�0�����ۉT:_����h��c�U�	�
��O�����{g\"���0�:�2���C�h���e!��>����\0�hgr1���~�����<�|�`h�-�t|J�d��T�Js��	�\0��;��r�ɘ�R�:%���g*�WI^ҩx�g8|?1�h
�2��t<�@fG�� ��\0(t�@ y��@GN��:��7<l���N?`U�(�UP�F�f�ȁ���ȇm`6�`�
o6zӅdZ�0�x����r���_�j-�2%�#�ث޽��\0�y����>H+��%�/.1�Z�7?xe\"�%�q�%Wq���
@�{�$Ԩ��\\���|j ����fؔ���^�L������G���.�Y�]����_u���T]^�Àp�>�%I;4�y�z[0�M�lx�N���!M9 F��4f)x�U�;r~(�/ta�f�Wh��,xw��zD�|#�}r9_�ܺ��J��ۖ�E�\'Eb��>�3��㡞���7P�����Y��Wx�6G��I��z���D��i7��P�5[�S��&�u�m��Y���$�5���̽��d�������Ḽ�+����:��,�\09Z�C͹�ua
�dc$�:���PB��i�������x2v�~½��z��i{zǳbĳ�bn���]usV�����Y׍b��8S�H1��Kؗ�Os���צ.�m�&\'�:l��2�ݓ�c�C1f��S*�ܔ�$ry�T���m�Bba[2��sUӻD��wR�$%�\\�b1=d_Ȫ.�zH�u�@�\0
�r�Ur�(�y%���
إB��)�I���\"�PUʪ r�b)�P��q����!���oYog�f��2uR^�����ŭ���/�_�O����O��wܿ`�o�ǲ��u���J
�e��4\\MR�!����mq�/��(dR3��m�e�T\"d7=�m�!֏K1x�/4�Δ\"cI��FEY(E=�(�
���1	��;����@P*�DH�����9�fjG��0JA|@+;
�Ħ�Vɑc�$�`Q�Ăe8;*����$��u���^�\\��g�B�`���>�}\"�S�3�t�,b+;\"	(��
� �;��﷞���C)�c��m��cf`���WM�.HS
jLL%Q3��j�`(�@!w��*�vұ�2�7\'��%:�������勛U�	�j�� �(����/�%Ie��$8� ;����L��,�gj5���ڬc!�8U����k���:@�ف��P߃c��},^�|O	�,f5�7�1��YKNQ]Y���
�8hZ�ndR�
���.<Cm=/}St���u<�}�t��&���fFH;����\' Ek�H�\"�G1[6W�a�@���L`΢c���N�_у;b�6�tte��V��^B?*/JEx��ao��\0
���e꣧�s���ӖjpN�� �\0�d$_v�/fk�+��P۟q�eH�����@|�{m��ޘ�q�󺵟>�P���d��~��9��ڏ��l��\"��\0��G@]�\0�~\"�н3����}a���L�q=�b�2�6\"�}�l8��T&�<�t{2���׭�r�?at�\"�l�4(>x�Y�s)ck<��l��d�\"G\'/�y�\0}澾:I��ʵ7/fx*���v��Z�Z�2��z���U�B=��)�2g��y�[��+̸���\"�,E|���eh�����w���G�8n��$�E!�T!{���o�)_��O���D���\\7�ޤ����LZYҕ	�J5Gq؞��N����ç����j�*��K�H{&Ct�V^E�dte�$/+)(�5e������?�E���a��H`��\0Hȏi�CSׄ1�gQ5%ln_��G��U����M���yaUL��6p�����\'/ ܳ�v�sT
�Y��\\�&�M9dރI���3l��A��ϛwUYB2)��:���&
�VF�L\'~o|.?�ok]�-�5�{�˝�P{8Ѫ.�~ʡ�fu�_�m���@6�}��}��v��JC�����bzj��E+��D�1���C��g2���J����+-;O������&r���<����)�\0\\�_-o,-��m������ׂ�������b������>x����$mL0�j;���#g���n�G�,�o
����T�8	�dH���:U޾����HI2�Q_9���R�JA�?�-�A���Z��@�W�M�̧�t�*ګ�.�}�\'&M������Z\'���JN_�!�%�Ȩ`9�7)��R��7@RP܃\'i �HLô`�+	0�-
�$���_K�V�En;Z\'8�٩�M��#��ј>���
_Oa�Η�W��2��8F�-Oj�@��
K�L���#M)��L��\"S�(�:�wh��X�ܲjs���tJ���	����QP���
�~�ys��u��Ur֩�D����wP7��ٗ�K����E��lD�)ܑ�D[b�(� R�.@�
���k�����v��cQ�s���U9����Zw%bF�=�SM��\'���L��\"�˙j�3�cr��˓��K���svHR����t	����ܟe�j��aඹCE���]muZ�8QX�/�$ߊ�][s®��G���.�:`qv�h�
ƌ�3r쉤�*�CO�X�� ��p[w���mkM�^]C�YomF��Ô��}dp�8�,.�����.E\"�7p�[�p
m\0��y�a~���Kr�ҫ�	Z�R���u;<j��2�y���d\0��ǅn��!@�.��{�
_��\0�����{E���ÙX�Xڽ6ɼ�
J2�;?��DD�!�MqF9�p�$A3�����r��㞱������}~��@W.�`֚VLΑ�C���휍��0A�bW���{p���r�ݱ�V�`��k1w(VDZU\"�X.�~5���v�t��\0�HeW0�L��@\'�j3��_���1�Sv�j|{�*<4tyY�m��yvČ�:�)H
9��r��#�\0���sw�o;bz<�0۵�!E@=UeY6Q�a �/��R�B�H�ʁD���O��l�>=�,W��W�]:C���^/�mWui�Ĥ��Ae��,�۹QH�6`�.���PJ��\"1�]���^ǉ�c�l}j����Uin�]Ͽ������)V���:%D��\0\0w8����	�Վe�W� i���|TN�t�5�ҚJ3J5rՠ���5�e4�M�% )�TP*�4i�9�f�*;�zz��yC���1��5�jf�)��-V*M���$Mv�9�����������/)A5���4Li~J�0����ښc��,#�G\"�t�$�82H���N}�@h���T��?��z%�<�\0�5R�ף�d�2
fW��ٺ �d3G�%�א�>`Wv~�a����j\\ˆEK��\\lK~���ovb�@�$ED ������`\'O���N�����V�銹^�\'l3�p��YjP�p���B;��V��b���?=Uu?�+>����t?�GQ�ص��Rf���i�l���z�:ic
�/Unْ)*N$U]��i���e!�gY5b�U�X��\\�Jv�YA��1���r��
�n����O�� qI=���٨?�t{IJ<vs�L�� A�\0_Š�*\'�>6�!������+��EU��e!hx�TLw��H�Z.]ø�S&\"�
5ցy��(R���Y}��ᣫ��=CF����μQ$�X�f�Ʈ�G�˓�c6RHQk�N���x��>�|�`�eU/(#)\\�;��h�t�ڙ9�R
�ҠĚN@�����U	�0��?��R�˧,+�lSZ��FS�.ѫy�m\0V��+�o�zM}�r�{��	���h	U�)���&s��&-�5�8[>���,�H�T�,|YJT�����~��-IOvnȵ�Vja>��*��L��{�v��d�ea�a��ʔ���7�]��8��r���NȪA})8�G�����(91���H���~}���r�t	(�[
�SG3���	�����\0ZU~bt�(�ܻd\00��q�*.JwG�/�P饿����1\\�;�<h)�|	G�:=�F��H�,²W����G0T�BT{bb�\\��v	�_�C�Si6�gl,��\";ꡪ/�tC���jص�Vk��^�AԆ�tߕ\'1}�e_�ݣ�i�6+f���E�0�j�N�a�+�ݓ&�s0��l6[he��B[��}Q�G^�J�u���o�·l(��XW��ދ�.�w^Zeּ:�g����ʽC���43��#b[c��ɢ���U*���Ar9vo#�T/�\'��K���ˌ�*���\0���wcy�վ��a��{�WrE\"Տ-���W��\'j���E��2Q��EQ�r�A�m�c�_1�B����^�>�c&�m#������u�-������H?tw�eg6��9
��6ʆ��\0/.�`>����as^H�#��0��̔���n�Z_�5�YWO�W
��fp-۵E4�	pp~��Q�@6t����i�ۑ��^�殡������I����a�+��eP��>�\'�@����/R����j��f��U��ya���	���+b�Q�C�
����O���\0F�+t���Y}oek�S�4L�5Py��K3 ���ℱQ�Vx����ۧ�������wM�3�~�[����q>����!�U�MqӴ�0�o.Z\0�|����u����F�Z�l��
��IP�p2�-Zx�ڭd�B˕[�*
~Ԛ&
��=[\\�+Xo~�p�T�\'�=u��&�u��ެ���:��U�>y��8�nJ(G�\"a\\l+.Q-�B�)����7�~��N^�#�JM��.��dZ���t|��)���� �m�h�F �����T�a�aq�6�_������X�,���7�W8j��.��AB�FZq3i�]�6ȴq��܄
���W�*�ֆS�����It��.���e#�K��)��
���fI��Ж7��ӄ���{�#����Dh���:U
?f���xs��ى���1;��J
�U����^�%z&�\".�*��UmM����*�)]޴,M��[�{L���n��1J�4j�&�i�FjZ*�Tb���ry$���rQ2��U�F\\�c͖?k&,�T��>��׏oN�T�y5� zҏ�.��$
�˹?	�U^���`r��2�q���z����S��ղu�@fcH�Mn8]��R����`} ���J>�t&\"\"9S<�SL��\'t˜���X\'���!�����a-����!��զ���A�R����n�~`_/Ū#�0�ZZK���zJ�7�\\��N#���P�-�qsS3��Ư9�ؾ֓�$�B1�>�u�$
����c��\0��v�5)�q�e�J=N��:���Uo�Tm[�����V�XY\'�)%-8`m�G��o��x��67>E�\"6�;}�U;{D=u-�z/�odlm�{ �e��B�I�5���/u]j��\"$Wd���J��9\'��`�b?g^K��4�Y�1L�Wo�\'��xJ��XD�&�a>qUr�27x��du�s`q�U��=.u�s[}�8kD�HΙ��
lc*�3(Kڠ�$½���������
O����:3�����釩>��}٩����K�K��c-�8ڛɇ�׋���&F_�=�~�߰#忐M�BE��}��`ߞ�]h��U��@KU�p����qm/	*�Q���v� p�
�-���_�������Ć�G�,�KKM�،G3���O�.�}�y
��N�\'g�Y_v�!\"�	
#�/tU�r��\0������ ���=O{=�>V4��̜�܎U�cUF��wh�_��b�ݻ�U�oBe��������<���o@�8 �W	cȧ;y���ݑ?�Tm忄�!�>Sm[J����?y��,�����yf�7�;�/���\0���-Q�k8�m��Y���b�7b�=Z3d�m�M$P\"E��i\"���������]���<&���
��J�����O
�ʵ��^1*N_,V�wI�ݸ���ǉ�D�<BG؇]?}�$���hfZ�%�g\"�_z�ܸQkD�o#�(6O�Ì̞Γn��v��p��9�ap�M5�e#�%�#��((�i�# Ԥ��Wq�
��D�\0ž�>P-�[��I�U��l6��U�}�=�/�xО\\
��\0�o�5ŝ��N���q0�n`C�^�cy��\"O���; \0\0	��S����\'̧O~>~��oߠL�2���w��.��O�\0�R����#��\0��!����bD1:\\0r��b	�Ѷ�~A���@b��H����oŠL�^;�;bP���),
��>@ۏ���!�/7ZвVS�/l�GT
�N��6
	T+����Ԫ%+��NGK(���>>�bǅv�����*֘���꺜̵��*��f��7����y:4�\"��A�\'�6��P�ؑ`d�F�+6�������vF�P����X+����\0W�W�/��S�32=��2���	����z�D�bI�c4L��|����$��~�ّp��^�?�g/g����=_~��ei1Zrz�=f�����O
*cy�2��|�>���t���\'�a���f����8���vb�4�&8���/�1L\"(+��I89w)�Ϸ�0W��,�����/�DΈ�����\\��|�[%���������PvR�.SN���S���4Z�!\\�J���-o�����+.yG�(�BCƨG;	ǳ��\01��8P��������*�Mo���Z}��W���iq��,�5���]=�E�y�_�)�Șk9 
	��M�}��t�ö���[?W�`�֕���N��[��\0����kCu����\0(��P��6�c���6J�<K	�1Y�䈐H�\0v*��sl����`��������V��3�o�����=[�{i�+~B����#��R��%�_��\0&�cVq>z�2=�JQT��~���b���<�Ȏv.
����&T��#XܵZ�[�^�j��ɖ*�F0f���V��D�M�A�U��QnBݓ0S�����٘1)S�3������խ��߱�S+��Gi��;�^6Yh6N���/��.�\'�_
��Q���R�Q�Q+<��C���c����uո	Jܝ������21�\"z+J�M{z�\'�Qph�q���ʹXԜSw^
�.�Ug)cI	8�%0����Q�0C��[�vX�}��m���\\,���ދ��_��d�+��8v�<k�甽;Ʒ$d��wrh���\"���c�o�%`��QV�59�:I[�����/k����2�rDj-S��\0쉝L;]��0��D����z��oQqW5� ڼ�MQ��G�jb%�������@%QO��.�V!�1U��HQ|Y����޷��&zh���~�2��2���Bu�a����M7%G\0O�-ɺ��#U�����F%�ߋ�5��\'ɯ������:i_�(�����ּ��w��m��_�/K���t��:Fu][�	��)3����H ��%C�5�r�R-�����^୻&����q6ZbN4O�R�6���.??�\0���.��f���aE��4�Q+Ԏ�ǭ�j�cMBZ_�������3��d�؎���������M{�Ե��A���_G������ ��C��������ۊ�\0��_MӶ���܊%*e0�.�P
ù�x�o�^\\���Ļ������||��1� �E�?��uK��(W\"�v����M�&;�M^ �W�&飔���ː� �#��:K�o#��1G���ŉ-�����
.OJ�؉$�I��I�I$�M.���D���`R~E��s�r�^\"�[�J\'�����}tľ�]\0q/��@K����}tľ�]\0�>.��)�� �O�4��cFO��K$���*��VD2�*&�V�G�fy�s�C�{��������;���2܏
�y�;����s��וX!�P��c�g�έ� >�W��#	r���͜q);`%Lx��m	�)��\"��s�.�Z��Sϗ��a��УF4Y�#������,�\'�tn~5���^w~5��7�F�����\0o������\0x����hl��t��Մn�jd��u]��J�#F�
+���׊f?t\'/p�;j�,��x��D���>O����k���0��\"䢓�U~�ù���`�V�U���/�\'B���7]g°�QN����ĺ��Be����5�TT_ߒ�[�l��y%�qZQ���
�������(�6�>���wN$UE���L��x��tE�C �<CW��hz�\0M��Sٖ��#�5Hz�T6ǐRq�l�_����VƄ�ݖ�b�VaZ�O#/<�×�c�3Bګ�D@�	�ET�ΓK
�������q�.��\0Xݡ��-(?ɃX��G��1�3�%?���JG��,���FINk���k�GY�uZ��ſߒS�q�\\�py\'������_������}o3M�<9�`U�\"�]��]U�a�o�V��:k������y��t����-hMK�IW������<��je��gZq}:܍a6r�KKr����K
9�:�C�\0b�yOr�nS$R�\0
- �P�y�H��5)�g���ʘ]���t��`����M�$�2��@I�y98���R�v��Q�����=��5j��)�Tu\"�ҝ[�ۭ;����|+�����c.��U�r��R���^��$�)�C�y{�I�t��G�.��YIjL�U��S㴰�.Y��>\'�A���i@mS�Z:���v�����mZ���A�H�ͺ�]v�r�\0�H����Q��:b֝����U�Ui���@�������,d�<�Iֶ`�f8�Q����(�M%�j+aS�s��������Ww^y�k�_��\0������+������\0�
�cR�m�����(�2\"�8��j9�Jd�E2�#h��`�I�
.N<ִ�E�7��MQ3���{�\\�ִ6ʔJ���;l����q�~
��$���u�+��Z�y���J�Fo �\0����z�����%�3�s�_
|��`z/�^#�Ph�\0���Z�b)�_@\0��4�
\0h@\0��4�
\0h@\0��4�
\0h@\0��4S/��\0��\0�:}�?��';;;

update pages_properties set background='#cccccc';;;
alter table navlinks
    add logo_bg		varchar(32) not null default '#ffffff' after left_img,
    add footer_text	text not null after logo_bg;;;
#update navlinks set footer_text='
#          <span class="BlueColor"><b>Omnistar Interactive</b></span><br>
#            6525 Belcrest Road, Suite 526<br>
#          Hyattsville, MD 20782<br>
#            <p class="VerdanaSmall">� Copyright 2005 Omnistar Interactive / All rights reserved<br>
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
