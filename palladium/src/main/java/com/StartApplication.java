package com;

import com.monitor.NettyServer;
import org.springframework.boot.Banner;
import org.springframework.boot.CommandLineRunner;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.builder.SpringApplicationBuilder;
import org.springframework.data.jpa.repository.config.EnableJpaAuditing;

/**
 * StartApplication
 *
 * @author cyw
 * @date 2020/02/27
 */
@EnableJpaAuditing
@SpringBootApplication(scanBasePackages = {"com.controller", "com.monitor"})
public class StartApplication implements CommandLineRunner {

  private final NettyServer nettyServer;

  public StartApplication(NettyServer nettyServer) {
    this.nettyServer = nettyServer;
  }

  public static void main(String[] args) {
    SpringApplication app = new SpringApplicationBuilder(StartApplication.class).application();
    app.setBannerMode(Banner.Mode.OFF);
    app.run(args);
  }

  @Override
  public void run(String... args) {
    nettyServer.start();
  }
}
