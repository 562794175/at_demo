package com.monitor;

import com.util.NetUtils;
import io.netty.bootstrap.ServerBootstrap;
import io.netty.buffer.PooledByteBufAllocator;
import io.netty.channel.ChannelFuture;
import io.netty.channel.ChannelOption;
import io.netty.channel.EventLoopGroup;
import io.netty.channel.nio.NioEventLoopGroup;
import io.netty.channel.socket.nio.NioServerSocketChannel;
import io.netty.util.concurrent.DefaultThreadFactory;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Component;

import javax.annotation.PreDestroy;
import javax.annotation.Resource;
import java.net.InetSocketAddress;
import java.util.Collection;
import java.util.HashSet;
import java.util.Map;

/** @author a.t */
@Component
public class NettyServer {

  private final Logger logger = LoggerFactory.getLogger(this.getClass());

  private final ServerBootstrap serverBootstrap = new ServerBootstrap();

  @Resource private NettyChannelHandler nettyChannelHandler;

  private EventLoopGroup boss;
  private EventLoopGroup work;

  private Integer bossThreads;
  private Integer workThreads;

  /** the cache for alive worker channel. <ip:port, dubbo channel> */
  private Map<String, NettyChannel> channels;

  /** the boss channel that receive connections and dispatch these to worker channel. */
  private io.netty.channel.Channel channel;

  private InetSocketAddress bindAddress;

  NettyServer() {
    boss = null;
    work = null;
    bossThreads = 1;
    workThreads = Runtime.getRuntime().availableProcessors() + 1;
    bindAddress = new InetSocketAddress(3460);
  }

  private void init() {
    workThreads = Math.min(workThreads, 32);
    boss = new NioEventLoopGroup(bossThreads, new DefaultThreadFactory("NettyServerBoss", true));
    work = new NioEventLoopGroup(workThreads, new DefaultThreadFactory("NettyServerWorker", true));
    channels = nettyChannelHandler.getChannels();
    serverBootstrap.group(boss, work);
    serverBootstrap.channel(NioServerSocketChannel.class);
    serverBootstrap.childOption(ChannelOption.SO_REUSEADDR, Boolean.TRUE);
    serverBootstrap.childOption(ChannelOption.TCP_NODELAY, Boolean.TRUE);
    serverBootstrap.childOption(ChannelOption.ALLOCATOR, PooledByteBufAllocator.DEFAULT);
    serverBootstrap.childHandler(new NettyChannelInitializer(nettyChannelHandler));
  }

  public void start() {
    init();
    try {
      // bind
      ChannelFuture channelFuture = serverBootstrap.bind(bindAddress);
      // channelFuture.syncUninterruptibly();
      channelFuture.sync();
      channel = channelFuture.channel();
      logger.info("netty server start!");
    } catch (Exception e) {
      logger.error(e.getMessage());
    }
  }

  @PreDestroy
  public void stop() {
    channel.close();
    stopChannels();
    boss.shutdownGracefully();
    work.shutdownGracefully();
    channels.clear();
    logger.info("netty server stop!");
  }

  private void stopChannels() {
    Collection<NettyChannel> channels = getChannels();
    if (channels != null && channels.size() > 0) {
      for (NettyChannel channel : channels) {
        try {
          channel.close();
        } catch (Throwable e) {
          logger.warn(e.getMessage(), e);
        }
      }
    }
  }

  private Collection<NettyChannel> getChannels() {
    Collection<NettyChannel> chs = new HashSet<>();
    for (NettyChannel channel : this.channels.values()) {
      if (channel.isConnected()) {
        chs.add(channel);
      } else {
        channels.remove(NetUtils.toAddressString(channel.getRemoteAddress()));
      }
    }
    return chs;
  }
}
