package com.controller;

import com.entity.User;
import com.monitor.NettyServer;
import com.monitor.Response;
import org.springframework.http.MediaType;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import javax.annotation.Resource;
import javax.servlet.http.HttpServletRequest;
import java.awt.*;
import java.util.Arrays;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;

@Controller
public class PortalController {

    private static final String USERNAME="815993";
    private static final String DEFAULTPWD="1";
    private static final String ENABLED="1";
    private static final String TIP1="You are great!";
    private static final String TIP2="Password error!";
    private static final String TIP3="Commit successful!";

    @Resource
    private NettyServer nettyServer;


    @GetMapping({"/ss"})
    public String index(Model model,@RequestParam(value = "inputPassword", defaultValue = DEFAULTPWD) String pwd) {
        User user = new User();
        user.setUserName(USERNAME);
        Calendar now = Calendar.getInstance();
        String time =String.format("%d%d",now.get(Calendar.HOUR_OF_DAY),now.get(Calendar.MINUTE));
        if(pwd.equals(time)){
            user.setTip(TIP3);
        } else if(pwd.equals(DEFAULTPWD)) {
            user.setTip(TIP1);
        } else {
            user.setTip(TIP2);
        }
        model.addAttribute("user", user);
        return "index";
    }

    @GetMapping({"/","/home","/index"})
    public String stoploss(Model model,@RequestParam(value = "enabled", defaultValue = ENABLED) String value) {
        model.addAttribute("enabled", value);
        return "stoploss";
    }

    @GetMapping(value = {"/stoploss/set"})
    @ResponseBody
    public String setStoploss(@RequestParam String enabled) {
        nettyServer.notifyAll(new Response("AUTO_STOPLOSS",enabled));
        return "true";
    }


}
