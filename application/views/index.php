<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <title>Puredark History </title>
    <link type="text/css" rel="stylesheet" href="css/index-2013.11.20.css"/>
    <link type="text/css" rel="stylesheet" href="css/jquery.mCustomScrollbar.css"/>
    <link type="text/css" rel="stylesheet" href="font/quicksand/stylesheet.css"/>
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="js/jquery.mCustomScrollbar.js"></script>
    <script type="text/javascript" src="js/jquery.form.min.js"></script>
    <script type="text/javascript" src="js/jquery.animate-colors.js"></script>
    <script type="text/javascript" src="js/plupload.full.min.js"></script>
    <script type="text/javascript" src="js/script-2013.11.20.js"></script>
    <!--    <script type="text/javascript" src="js/prefixfree.min.js"></script>-->
    <script type="text/javascript">
    </script>
    <script type="text/javascript">
        //			(function (i, s, o, g, r, a, m) {
        //				i['GoogleAnalyticsObject'] = r;
        //				i[r] = i[r] ||
        //				function () {
        //					( i[r].q = i[r].q || [] ).push(arguments)
        //				} , i[r].l = 1 * new Date();
        //				a = s.createElement(o) , m = s.getElementsByTagName(o)[0];
        //				a.async = 1;
        //				a.src = g;
        //				m.parentNode.insertBefore(a, m)
        //			})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
        //			ga('create', 'UA-39765420-2', 'skinshop.tk');
        //			ga('send', 'pageview');
        $(document).ready(function () {
            $('.code').click(function () {
                $this = $(this);
                $.ajax({
                    url: "code",
                    type: "post",
                    success: function (img_path) {
                        $('.code').attr("src", img_path);
                    }
                })
            });
            $('.code').click();
            $('#menu dl:last dd:last').click();
        });
    </script>

</head>
<body>
<div id="menu">
    <form class="globalWidth">
        <?php
        if (count($year_array)) {
            foreach ($year_array as $key => $year) {
                echo '<span class="year" alt=' . $key . '><b>' . $key . '</b>';
                foreach ($year as $month => $day) {
                    echo "<dl><dt alt='$month'>" . preg_replace("/^(0+)/", '', $month) . "</dt>";
                    foreach ($day as $value) {
                        echo "<dd alt='$value'>" . preg_replace("/^(0+)/", '', $value) . "</dd>";
                    }
                    echo '</dl>';
                }
                echo "</span>";
            }
        }
        ?>

        <?php

        if (isset($log_in) && $log_in === TRUE) {
            echo '<a title="INPUT" id="insect_btn"> + </a>';
            echo '<a title="' . $username . '">' . $username . '</a>';
            echo '<a title="SIGN OUT" id="sign_out_btn"> O </a>';
        } else {
            echo '<a title="SIGN UP" id="sign_up_btn"> R </a>';
            echo '<a title="SIGN IN" id="sign_in_btn"> L </a>';
        }
        ?>
        <!--				<a title="TEXT" id="text_btn"> T </a>-->
        <!--				<a title="CATEGORY" id="category_btn"> C </a>-->
    </form>
</div>
<div id="main" class="loading">
    <div id="logo">
        <h1 class="globalWidth">Ruo's History</h1>
    </div>
    <div id="content">
        <?php
        //        var_dump($this->session->all_userdata());
        ?>
        <div class="globalWidth"></div>
    </div>
    <div id="footer">
        <p class="globalWidth">
            Designed by Ruo. Powered by Puredark - 2016.
        </p>
    </div>
</div>
<div id="sign_up" class="sidemenu">
    <form action="sign_up" method="post">
        <dl>
            <dt>Sign Up</dt>
            <dd>
                <label for="username">
                    Username
                    <input type="text" id="username" name="username" required="required"/>
                    <div class="tip"></div>
                </label>
            </dd>
            <dd>
                <label for="password">
                    Password
                    <input type="password" id="password" name="password" required="required"/>
                    <div class="tip"></div>
                </label>
            </dd>
            <dd>
                <label for="password_confirm">
                    Confirm Password
                    <input type="password" id="password_confirm" name="password_confirm" required="required"/>
                    <div class="tip"></div>
                </label>
            </dd>
            <dd>
                <label for="code">
                    Code
                    <input type="text" id="code" name="code" required="required"/>
                    <img class="code"/>
                    <div class="tip"></div>
                </label>
            </dd>
            <dd>
                <input name="sign_up" type="submit" value="Sign Up"/>
            </dd>
        </dl>
    </form>
</div>
<div id="sign_in" class="sidemenu">
    <form action="sign_in" method="post">
        <dl>
            <dt>Sign In</dt>
            <dd>
                <label for="username">
                    Username
                    <input type="text" id="username" name="username" required="required"/>
                </label>

            </dd>
            <dd>
                <label for="password">
                    Password
                    <input type="password" id="password" name="password" required="required"/>
                </label>
            </dd>
            <dd>
                <label for="code">
                    Code
                    <input type="text" id="code" name="code" required="required"/>
                    <img class="code"/>
                </label>
            </dd>
            <dd>
                <input name="sign_in" type="submit" value="Sign In"/>
            </dd>
        </dl>
    </form>
</div>
<!--		<div id="category" class="sidemenu">-->
<!--			<dl>-->
<!--				<dt>-->
<!--					Category1-->
<!--				</dt>-->
<!--				<dd>-->
<!--					Magic-->
<!--				</dd>-->
<!--				<dd>-->
<!--					Pictures-->
<!--				</dd>-->
<!--				<dd>-->
<!--					Pets-->
<!--				</dd>-->
<!--				<dd>-->
<!--					Art-->
<!--				</dd>-->
<!--			</dl>-->
<!--		</div>-->
<div id="insect" class="sidemenu">
    <span id="loading_bar"></span>

    <form action="upload" method="post" enctype="multipart/form-data">
        <dl>
            <dt>
                Insect Menu
            </dt>
            <dd id="textarea">
                Text
                <textarea name="tucao"></textarea>
            </dd>
            <dd id="select_files">
                Select File ( Music / Photo )
                <input id="file_upload" name="file_upload[]" type="file" multiple>
            </dd>
            <dd>
                <label for="code">
                    Code
                    <input type="text" id="code" name="code"/>
                    <img class="code"/>
                </label>
            </dd>
            <dd id="upload_files">
                Upload
            </dd>
            <dd id="close_insect">
                Close
            </dd>
        </dl>
    </form>
</div>
<div id="tools"></div>
<script type="text/javascript">


</script>
<!--[if IE 6 ]><p class="warning">您的浏览器版本过低！</p><![endif]-->
<!--[if IE 7 ]><p class="warning">您的浏览器版本过低！</p><![endif]-->
</body>
</html>