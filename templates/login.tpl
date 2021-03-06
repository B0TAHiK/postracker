{% extends "baseNoAuth.tpl" %}

{% block title %}Log in{% endblock %}

{% block pageName %}Login Form{% endblock %}

{% block content %}
{% if loggedIN == 1 %}
    <div class="alert alert-danger" role="alert"> You already logged in!</div>
{% else %}
    {% if fromPost == '1' %}
        {% if success == '1' %}
               <div class="alert alert-success" role="alert">You logged in. You will be redirected shortly.</div>
               <script type="text/javascript">
                    var delay = 1000;
                    setTimeout("document.location.href='/index.php'", delay);
                </script>
        {% else %}
        {% set errorClass = 'has-error' %}
            <div class="alert alert-danger" role="alert">Wrong login or password!</div>
        {% endif %}
    {% endif %}
            <form action="login.php" method="post" class="form-horizontal" style="width: 45%;margin: 0 auto;">
            <div class="form-group">
                <label for="inputEmail" class="col-sm-2 control-label">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email">
                </div>
            </div>
            <div class="form-group {{errorClass}}">
                <label for="inputPassword" class="col-sm-2 control-label">Password:</label>
                 <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password">
                </div>
            </div>
            <input type=hidden name="go" value="sent">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default" id="submit">Sign in</button>
                </div>
            </div>
            </form>
{% endif %}
{% endblock %}