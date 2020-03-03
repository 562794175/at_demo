package com.monitor;

import io.netty.bootstrap.ServerBootstrap;
import io.netty.channel.EventLoopGroup;
import io.netty.channel.nio.NioEventLoopGroup;
import org.springframework.stereotype.Component;

import javax.annotation.PreDestroy;

@Component
public class NettyServer {

    ServerBootstrap serverBootstrap = new ServerBootstrap();

    EventLoopGroup boss = new NioEventLoopGroup();
    EventLoopGroup work = new NioEventLoopGroup();

    public void start() {
        System.out.println("netty server start!");
    }

    @PreDestroy
    public void stop() {
        System.out.println("netty server stop!");
    }

}
