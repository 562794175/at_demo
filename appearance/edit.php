<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" href="jquery/jquery-ui.css">
<style>
#datepicker {
    width:85px;
}
.ui-accordion .ui-accordion-content {
    padding:0.2em;
}
.ui-controlgroup {
    vertical-align: baseline;
}
.ui-controlgroup .ui-selectmenu-button {
    width:80px;
}
.ui-controlgroup label {
    padding: 5px;
}
fieldset .ui-button {
    text-align: left;
}
</style>
<style>
    img{
            border:none;display: block
    }
    .box{
            width: 100%;margin: 6px auto;
    }
    .imgFileUploade .header{
            width: 100%;clear:both;height:30px;
    }
    .imgFileUploade .header span{
            display: block;float:left;
    }
    .imgFileUploade .header span.imgTitle{
            line-height:30px;margin-left: 6px;
    }
    .imgFileUploade .header span.imgClick{
            width: 30px;height: 30px;margin-left: 10px;cursor: pointer;
            background: url(img/addUpload.png) no-repeat center center;background-size:cover; 
    }
    .imgFileUploade .header span.imgcontent{
/*            color:#999;margin-left:120px;line-height: 50px;*/
    }
    .imgFileUploade .imgAll{
            width: 100%;
    }
    .imgFileUploade .imgAll ul:after{ 
            visibility: hidden;  display: block; font-size: 0; content: ".";  clear: both; height: 0;padding-top:20px;
    } 
    .imgFileUploade .imgAll li{
            width: 100px;height: 100px;border:solid 1px #ccc;margin:8px 5px;float: left;position: relative;box-shadow: 0 0 10px #eee;list-style: none;
    }
    .imgFileUploade .imgAll li img{
            position: absolute;top:0;left:0;width: 100%;height: 100%;display: block;
    }
    .delImg{
            position: absolute;top:-10px;right:-7px;width: 22px;height: 22px;background: #000;border-radius: 50%;display: block;text-align:  center;line-height: 22px;color:#fff;font-weight: 700;font-style:normal;cursor: pointer;
    }
    .box{
            border:solid 1px #ccc;
    }
</style>
</head>
<body>
    <div class="widget">
        <div class="controlgroup">
                <label class="ui-widget ui-controlgroup-item">日期：</label>
                <input readonly="readonly" id="datepicker" class="ui-widget ui-controlgroup-item ui-button">
                <label class="ui-widget ui-controlgroup-item">时间：</label>
                <select id="time" >
                  <option>00:00</option>
                  <option>01:00</option>
                  <option>02:00</option>
                  <option>00:00</option>
                  <option>01:00</option>
                  <option>02:00</option>
                </select>
        </div>
        <div class="ui-state-highlight ui-corner-all" style="padding: 3px; height:30px; ">
            <span class="ui-icon ui-icon-unlocked" style="float: left;margin-right: .3em;"></span>
            <strong>周期</strong>
            <select style="padding:5px;">
                <option>1H</option>
                <option>4H</option>
                <option>1D</option>
            </select>
            <strong>状态</strong>
            <select style="padding:5px;">
                <option>00:00</option>
                <option>01:00</option>
                <option>02:00</option>
                <option>00:00</option>
                <option>01:00</option>
                <option>02:00</option>
            </select>
        </div>
        <image src="pic1.png" width="100%" border="0">
        <div id="accordion">
            <h3>Section 1</h3>
            <div>

                <fieldset>
                  <legend>Select a Location: </legend>
                  <label for="radio-1">New York</label>
                  <input type="radio" name="radio-1" id="radio-1">
                  <label for="radio-2">Paris</label>
                  <input type="radio" name="radio-1" id="radio-2">
                  <label for="radio-3">London</label>
                  <input type="radio" name="radio-1" id="radio-3">
                </fieldset>

    
                <fieldset>
                  <legend>Hotel Ratings: </legend>
                  <label for="checkbox-1">Mauris mauris ante, blandit et</label>
                  <input type="checkbox" name="checkbox-1" id="checkbox-1">
                  <label for="checkbox-2">ultrices a, suscipit eget, quam.</label>
                  <input type="checkbox" name="checkbox-2" id="checkbox-2">
                  <label for="checkbox-3">Vivamus nisi metus, molestie vel, gravida in, condimentum sit amet, nunc.</label>
                  <input type="checkbox" name="checkbox-3" id="checkbox-3">
                  <label for="checkbox-4">Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut odio. </label>
                  <input type="checkbox" name="checkbox-4" id="checkbox-4">
                </fieldset>


                
                
            </div>
          </div>


</div>
    
    <div class="box ui-corner-all"></div>
    
    <div>
        &emsp;<button id="history">返&emsp;回</button>&emsp;<button id="save">保&emsp;存</button>&emsp;<button id="save">自动生成图片</button>
    </div>
    <div id="dialog-confirm" title="Empty the recycle bin?">
      <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
    </div>

    
    
<script src="jquery/jquery-1.12.4.js"></script>
<script src="jquery/jquery-ui.js"></script>
<script src="jquery/imgFileupload.js"></script>
<script>
$( function() {
    $(".controlgroup").controlgroup({"direction": "horizontal"});
    $( "#accordion" ).accordion({heightStyle: "content"});
    $( "#accordion input" ).checkboxradio();
  
    $( "#dialog-confirm" ).dialog({
        autoOpen: false
      });
    $( "#save" ).on( "click", function() {
        $( "#dialog-confirm" ).dialog({
          autoOpen: true,
          resizable: false,
          height: "auto",
          width: 300,
          modal: true,
          buttons: {
            Cancel: function() {
              $( this ).dialog( "close" );
            }
          }
        });
    });
} );
</script>

<script type="text/javascript">
    var imgFile = new ImgUploadeFiles('.box',function(e){
            this.init({
                    MAX : 3, //限制个数
                    MH : 5800, //像素限制高度
                    MW : 5900, //像素限制宽度
                    callback : function(arr){
                            console.log(arr)
                    }
            });
    });
</script>
    
    
</body>
</html>
