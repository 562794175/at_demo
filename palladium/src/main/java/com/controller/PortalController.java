package com.controller;

import com.entity.User;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;

import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;

@Controller
public class PortalController {

    @GetMapping({"/","/home"})
    public String index(Model model) {
        User user = new User();
        user.setUserName("815993");
        model.addAttribute("user", user);
        return "index";
    }
}
