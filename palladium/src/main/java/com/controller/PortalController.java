package com.controller;

import com.entity.Quote;
import com.entity.User;
import com.monitor.NettyServer;
import com.monitor.Response;
import com.repository.QuoteRepository;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.ResponseBody;

import javax.annotation.Resource;
import java.util.Calendar;

@Controller
public class PortalController {

  private static final String USERNAME = "815993";
  private static final String DEFAULTPWD = "1";
  private static final String ENABLED = "1";
  private static final String TIP1 = "You are great!";
  private static final String TIP2 = "Password error!";
  private static final String TIP3 = "Commit successful!";

  @Resource private NettyServer nettyServer;

  @Resource private QuoteRepository quoteRepository;

  @GetMapping({"/drop"})
  public String index(Model model) {
    return "index";
  }

  @GetMapping({"/", "/home", "/index"})
  public String stoploss(Model model) {
    Quote quote = nettyServer.getQuote();
    if (quote != null) {
      String tip = quote.getAsk() + " | " + quote.getBid() + " | ";
      tip += (quote.getAutoStopLoss() == 1 ? "Y" : "N");
      model.addAttribute("tip", tip);
    }
    return "stoploss";
  }

  @GetMapping(value = {"/stoploss/set"})
  @ResponseBody
  public String setStoploss(@RequestParam String enabled) {
    nettyServer.notifyAll(new Response("AUTO_STOPLOSS", enabled));
    return "true";
  }
}
