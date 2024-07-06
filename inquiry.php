<?php include("admincp/config.php");

$page = get_page(17);

if( items_in_basket() < 1 ){ redirect(_root_."basket"); }

$title = $page['title'] . " - " .$title;
$keywords = $page['keywords'];
$description = $page['description'];

if( !empty($page['img_banner']) ){ $img_banner = $__url_attimgs.$page['img_banner']; }

$loadtabs=true; include("common/header.php"); ?>

         <div class="rs-breadcrumbs">
            <img src="<?php _e(_root_); ?>assets/images/bg/about-bg.jpg" alt="">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page-title"><?php _e($page['title']); ?></h1>
                            <ul>
                                <li>
                                    <a class="active" href="<?php _e(_root_); ?>">Home</a>
                                </li>
                                <li><?php _e($page['title']); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rs-check-out section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-bg"><?php _e($page['title']); ?></div>
                        <div class="check-out-box">
                            <form id="contact-form" method="post" action="<?php _e(_root_); ?>common/process.php" onSubmit="return validateForm(['fname', 'ph', 'fax', 'email', 'country', 'address', 'message'], '!');">
                                <input type="hidden" name="_p" value="sendinq">
                                <fieldset>
                                    <div class="row">                                      
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Full Name*</label>
                                                <input name="fname" id="fname" class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Company Name*</label>
                                                <input name="fax" id="fax" class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Email*</label>
                                                <input id="email" name="email" class="form-control" type="email">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Phone*</label>
                                                <input name="ph" id="ph" class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-12 col-sm-12 col-xs-12">    
                                            <div class="form-group">
                                                <label>Select Country*</label>
                                                <select name="country" id="country" class="form-control"><option value="" selected="">Select Country</option><option value="AFGHANISTAN">AFGHANISTAN</option><option value="ALBANIA">ALBANIA</option><option value="ALGERIA">ALGERIA</option><option value="AMERICAN-SAMOA">AMERICAN SAMOA</option><option value="ANDORRA">ANDORRA</option><option value="ANGOLA">ANGOLA</option><option value="ANGUILLA">ANGUILLA</option><option value="ANTARCTICA">ANTARCTICA</option><option value="ANTIGUA-AND-BARBUDA">ANTIGUA AND BARBUDA</option><option value="ARGENTINA">ARGENTINA</option><option value="ARMENIA">ARMENIA</option><option value="ARUBA">ARUBA</option><option value="AUSTRALIA">AUSTRALIA</option><option value="AUSTRIA">AUSTRIA</option><option value="AZERBAIJAN">AZERBAIJAN</option><option value="BAHAMAS">BAHAMAS</option><option value="BAHRAIN">BAHRAIN</option><option value="BANGLADESH">BANGLADESH</option><option value="BARBADOS">BARBADOS</option><option value="BELARUS">BELARUS</option><option value="BELGIUM">BELGIUM</option><option value="BELIZE">BELIZE</option><option value="BENIN">BENIN</option><option value="BERMUDA">BERMUDA</option><option value="BHUTAN">BHUTAN</option><option value="BOLIVIA">BOLIVIA</option><option value="BOSNIA-HERZOGOV">BOSNIA-HERZOGOV</option><option value="BOTSWANA">BOTSWANA</option><option value="Bouvet-Island">Bouvet Island</option><option value="BRAZIL">BRAZIL</option><option value="BRUNEI">BRUNEI</option><option value="BULGARIA">BULGARIA</option><option value="BURKINA-FASO">BURKINA FASO</option><option value="BURUNDI">BURUNDI</option><option value="CAMBODIA">CAMBODIA</option><option value="CAMEROON">CAMEROON</option><option value="CANADA">CANADA</option><option value="CAPE-VERDE-ISLA">CAPE VERDE ISLA</option><option value="CAYMAN-ISLANDS">CAYMAN ISLANDS</option><option value="CENTRAL-AFRICAN">CENTRAL AFRICAN</option><option value="CHAD">CHAD</option><option value="CHILE-SANTIAG">CHILE - SANTIAG</option><option value="CHINA">CHINA</option><option value="CHRISTMAS-ISLAND-CC">CHRISTMAS ISLAND CC</option><option value="COLOMBIA">COLOMBIA</option><option value="CONGO">CONGO</option><option value="COOK-ISLANDS">COOK ISLANDS</option><option value="COSTA-RICA">COSTA RICA</option><option value="CROATIA">CROATIA</option><option value="CUBA">CUBA</option><option value="CYPRUS">CYPRUS</option><option value="CZECHOSLAVAKIA">CZECHOSLAVAKIA</option><option value="DENMARK">DENMARK</option><option value="DJIBOUTI">DJIBOUTI</option><option value="DOMINICA">DOMINICA</option><option value="DOMINICAN-REPUBLIC">DOMINICAN REPUBLIC</option><option value="ECUADOR">ECUADOR</option><option value="EGYPT">EGYPT</option><option value="EL-SALVADOR">EL SALVADOR</option><option value="EQUATORIAL-GUINEA">EQUATORIAL GUINEA</option><option value="ESTONIA">ESTONIA</option><option value="ETHIOPIA">ETHIOPIA</option><option value="FALKLAND-ISLES">FALKLAND ISLES</option><option value="FAROE-ISLANDS">FAROE ISLANDS</option><option value="FIJI-ISLANDS">FIJI ISLANDS</option><option value="FINLAND">FINLAND</option><option value="FRANCE">FRANCE</option><option value="FRANCE(EUROPEAN TER.)">FRANCE (EUROPEAN TER.)</option><option value="FRENCH-SOUTHERN-TERR.">FRENCH SOUTHERN TERR.</option><option value="GABON">GABON</option><option value="GAMBIA">GAMBIA</option><option value="GEORGIA">GEORGIA</option><option value="GERMANY">GERMANY</option><option value="GHANA">GHANA</option><option value="GIBRALTAR">GIBRALTAR</option><option value="GREECE">GREECE</option><option value="GREENLAND">GREENLAND</option><option value="GRENADA">GRENADA</option><option value="GUADELOUPE">GUADELOUPE</option><option value="GUAM">GUAM</option><option value="GUATEMALA">GUATEMALA</option><option value="GUINEA">GUINEA</option><option value="GUINEA-BISSAU">GUINEA-BISSAU</option><option value="GUYANA">GUYANA</option><option value="HAITI">HAITI</option><option value="HONDURAS">HONDURAS</option><option value="HONG-KONG">HONG KONG</option><option value="HUNGARY">HUNGARY</option><option value="ICELAND">ICELAND</option><option value="INDIA">INDIA</option><option value="INDONESIA">INDONESIA</option><option value="IRAN">IRAN</option><option value="IRAQ">IRAQ</option><option value="ISRAEL">ISRAEL</option><option value="ITALY">ITALY</option><option value="IVORY-COAST">IVORY COAST</option><option value="JAMAICA">JAMAICA</option><option value="JAPAN">JAPAN</option><option value="JORDAN">JORDAN</option><option value="KAZAKHSTAN">KAZAKHSTAN</option><option value="KENYA">KENYA</option><option value="KIRIBATI">KIRIBATI</option><option value="KOREA-NORTH">KOREA NORTH</option><option value="KOREA-SOUTH">KOREA SOUTH</option><option value="KUWAIT">KUWAIT</option><option value="KYRGYZSTAN">KYRGYZSTAN</option><option value="LAOS">LAOS</option><option value="LATVIA">LATVIA</option><option value="LEBANON">LEBANON</option><option value="LESOTHO">LESOTHO</option><option value="LIBERIA">LIBERIA</option><option value="LIBYA">LIBYA</option><option value="LIECHTENSTEIN">LIECHTENSTEIN</option><option value="LITHUANIA">LITHUANIA</option><option value="LUXEMBOURG">LUXEMBOURG</option><option value="MACAU">MACAU</option><option value="MACEDONIA">MACEDONIA</option><option value="MADAGASCAR">MADAGASCAR</option><option value="MALAWI">MALAWI</option><option value="MALAYSIA">MALAYSIA</option><option value="MALDIVES">MALDIVES</option><option value="MALI-REPUBLIC">MALI REPUBLIC</option><option value="MALTA">MALTA</option><option value="MARSHALL-ISLES">MARSHALL ISLES</option><option value="MARTINIQUE">MARTINIQUE</option><option value="MAURITANIA">MAURITANIA</option><option value="MAURITIUS">MAURITIUS</option><option value="MEXICO">MEXICO</option><option value="MICRONESIA">MICRONESIA</option><option value="MOLDAVIA">MOLDAVIA</option><option value="MONACO">MONACO</option><option value="MONGOLIA">MONGOLIA</option><option value="MONTSERRAT">MONTSERRAT</option><option value="MOROCCO">MOROCCO</option><option value="MOZAMBIQUE">MOZAMBIQUE</option><option value="MYANMAR">MYANMAR</option><option value="NAMIBIA">NAMIBIA</option><option value="NAURU">NAURU</option><option value="NEPAL">NEPAL</option><option value="NETH.ANTILLES">NETH. ANTILLES</option><option value="NETHERLANDS">NETHERLANDS</option><option value="NEW-CALEDONIA">NEW CALEDONIA</option><option value="NEW-ZEALAND">NEW ZEALAND</option><option value="NICARAGUA">NICARAGUA</option><option value="NIGER">NIGER</option><option value="NIGERIA">NIGERIA</option><option value="NIUE-ISLAND">NIUE ISLAND</option><option value="NORFOLK-ISLAND">NORFOLK ISLAND</option><option value="NORTHERN-MARIANA-ISLANDS">NORTHERN MARIANA ISLANDS</option><option value="NORWAY">NORWAY</option><option value="OMAN">OMAN</option><option value="PAKISTAN">PAKISTAN</option><option value="PALAU">PALAU</option><option value="PANAMA">PANAMA</option><option value="PAPUA-NEW-GUINE">PAPUA NEW GUINE</option><option value="PARAGUAY">PARAGUAY</option><option value="PERU">PERU</option><option value="PHILLIPINES">PHILLIPINES</option><option value="PITCAIRN-ISLANDS">PITCAIRN ISLANDS</option><option value="POLAND">POLAND</option><option value="PORTUGAL">PORTUGAL</option><option value="PUERTO-RICO">PUERTO RICO</option><option value="QATAR">QATAR</option><option value="REUNION-ISLES.">REUNION ISLES.</option><option value="ROMANIA">ROMANIA</option><option value="RUSSIA">RUSSIA</option><option value="RWANDA">RWANDA</option><option value="SAMOA">SAMOA</option><option value="SAN-MARINO">SAN MARINO</option><option value="SAUDIA-ARABIA">SAUDIA ARABIA</option><option value="SENEGAL-REPUBLI">SENEGAL REPUBLI</option><option value="SEYCHELLES">SEYCHELLES</option><option value="SIERRA-LEONE">SIERRA LEONE</option><option value="SINGAPORE">SINGAPORE</option><option value="SLOVAK-REPUBLIC">SLOVAK REPUBLIC</option><option value="SLOVENIA">SLOVENIA</option><option value="SOLOMON-ISLES">SOLOMON ISLES</option><option value="SOMALIA">SOMALIA</option><option value="SOUTH-AFRICA">SOUTH AFRICA</option><option value="SPAIN">SPAIN</option><option value="SRI-LANKA">SRI LANKA</option><option value="ST.HELENA">ST. HELENA</option><option value="ST.KITTS-AND-NEVIS">ST. KITTS AND NEVIS</option><option value="ST.LUCIA">ST. LUCIA</option><option value="ST.VINCENT-AND-GRENADINES">ST. VINCENT AND GRENADINES</option><option value="ST.PIERRE/MIQU">ST.PIERRE/ MIQU</option><option value="SUDAN">SUDAN</option><option value="SURINAME">SURINAME</option><option value="SWAZILAND">SWAZILAND</option><option value="SWEDEN">SWEDEN</option><option value="SWITZERLAND">SWITZERLAND</option><option value="SYRIA">SYRIA</option><option value="TADJIKISTAN">TADJIKISTAN</option><option value="TAIWAN">TAIWAN</option><option value="TANZANIA">TANZANIA</option><option value="THAILAND">THAILAND</option><option value="TOGO">TOGO</option><option value="TOKELAU">TOKELAU</option><option value="TONGA">TONGA</option><option value="TRINIDAD-&amp;-TOBAGO">TRINIDAD &amp; TOBAGO</option><option value="TUNISIA">TUNISIA</option><option value="TURKEY">TURKEY</option><option value="TURKMENISTAN">TURKMENISTAN</option><option value="TURKS-AND-CAICOS-ISLANDS">TURKS AND CAICOS ISLANDS</option><option value="TUVALU">TUVALU</option><option value="UGANDA">UGANDA</option><option value="UKRAINE">UKRAINE</option><option value="UNITED-ARAB-EMI">UNITED ARAB EMI</option><option value="UNITED-KINGDOM">UNITED KINGDOM</option><option value="UNITED-STATES">UNITED STATES</option><option value="URUGUAY">URUGUAY</option><option value="UZBEKISTAN">UZBEKISTAN</option><option value="VANUATU">VANUATU</option><option value="VATICAN-CITY">VATICAN CITY</option><option value="VENEZUELA">VENEZUELA</option><option value="VIETNAM">VIETNAM</option><option value="VIRGIN-ISLANDS-(BRITISH)">VIRGIN ISLANDS (BRITISH)</option><option value="WALLIS-&amp;-FUTUNA">WALLIS &amp; FUTUNA</option><option value="WESTERN-SAHARA">WESTERN SAHARA</option><option value="YEMEN">YEMEN</option><option value="YUGOSLAVIA">YUGOSLAVIA</option><option value="ZAIRE">ZAIRE</option><option value="ZAMBIA">ZAMBIA</option><option value="ZIMBABWE">ZIMBABWE</option></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-12 col-sm-12 col-xs-12">    
                                            <div class="form-group">
                                                <label>Addreess*</label>
                                                <textarea class="form-control" name="address" id="address" cols="30" rows="6" placeholder="Enter your Address, e.g. 45 Ocean Street, SYDNEY New South Wales"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-12 col-sm-12 col-xs-12">    
                                            <div class="form-group">
                                                <label>Message*</label>
                                                <textarea class="form-control" name="message" id="message" cols="30" rows="6" placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="rs-payment-system"><input class="btn-send" type="suBMIt" value="Place Inquiry"></div>
                            </form> 
                        </div>                      
                    </div>
                </div><!-- .row -->
            </div><!-- .container -->
        </div>
	
	<?php include("common/footer.php"); ?>

</body>
</html>