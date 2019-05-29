<?php
require_once("function.php");
require_once("base-temp.php");
?>
<body>
<div class="container-fluid" id="app">
    <header-temp></header-temp>
</div>
</body>
<script src="vue.js"></script>

<script>
    Vue.component('header-temp', {template: '#header-temp'});
    var vm = new Vue({el: "#app"});
</script>
