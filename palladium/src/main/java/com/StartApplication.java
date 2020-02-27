package com;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.builder.SpringApplicationBuilder;

/**
 * StartApplication
 *
 * @author cyw
 * @date 2020/02/27
 */

@SpringBootApplication(scanBasePackages = { "com.controller","com.monitor"})
public class StartApplication {
    public static void main(String[] args) {
        SpringApplication app = new SpringApplicationBuilder(StartApplication.class).application();
        app.run(args);
    }
}
