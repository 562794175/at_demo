package com;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.builder.SpringApplicationBuilder;


@SpringBootApplication(scanBasePackages = { "com.admin", "com.portal","com.monitor"})
public class StartApplication {
    public static void main(String[] args) {
        SpringApplication app = new SpringApplicationBuilder(StartApplication.class).application();
        app.run(args);
    }
}
