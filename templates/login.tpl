{% extends "base.tpl" %}

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
                    var delay = 5000;
                    setTimeout("document.location.href='/index.php'", delay);
                </script>
        {% else %}
            <div class="alert alert-danger" role="alert">Wrong login or password!</div>
        {% endif %}
    {% endif %}
            <form action="login.php" method="post" class="login" style="width: 33%;margin: 0 auto;">
            <div class="form-group" width="25%">
                <label for="inputEmail">Email</label>
                <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email">
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password">
            </div>
            <input type=hidden name="go" value="sent">
            <button type="submit" class="btn btn-primary" id="submit">Login</button>
            </form>
{% endif %}
{% endblock %}