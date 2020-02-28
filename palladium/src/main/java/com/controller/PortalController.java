package com.controller;

import com.entity.User;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.ResponseBody;

import javax.servlet.http.HttpServletRequest;
import java.util.Arrays;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;

@Controller
public class PortalController {

    private final String USERNAME="815993";
    private final String DEFAULTPWD="1";
    private final String TIP1="You are great!";
    private final String TIP2="Password error!";
    private final String TIP3="Commit successful!";


    @GetMapping({"/","/home","/index"})
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

}
