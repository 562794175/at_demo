package com.monitor;

import com.alibaba.fastjson.JSONObject;
import com.util.NetUtils;
import io.netty.channel.ChannelDuplexHandler;
import io.netty.channel.ChannelHandler;
import io.netty.channel.ChannelHandlerContext;
import io.netty.channel.ChannelPromise;
import io.netty.handler.timeout.IdleStateEvent;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Component;

import javax.annotation.Resource;
import java.net.InetSocketAddress;
import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;

/** @author a.t */
@ChannelHandler.Sharable
@Component
public class NettyChannelHandler extends ChannelDuplexHandler {

  private static final Logger logger = LoggerFactory.getLogger(NettyChannelHandler.class);
  /** the cache for alive worker channel. <ip:port, channel> */
  private final Map<String, NettyChannel> channels = new ConcurrentHashMap<>();

  @Resource private RequestHandler requestHandler;

  public Map<String, NettyChannel> getChannels() {
    return channels;
  }

  @Override
  public void channelActive(ChannelHandlerContext ctx) throws Exception {
    NettyChannel channel = NettyChannel.getOrAddChannel(ctx.channel());
    try {
      if (channel != null) {
        channels.put(
            NetUtils.toAddressString((InetSocketAddress) ctx.channel().remoteAddress()), channel);
      }
    } finally {
      NettyChannel.removeChannelIfDisconnected(ctx.channel());
    }
  }

  @Override
  public void channelInactive(ChannelHandlerContext ctx) throws Exception {
    NettyChannel channel = NettyChannel.getOrAddChannel(ctx.channel());
    try {
      if (channel != null) {
        channels.remove(
            NetUtils.toAddressString((InetSocketAddress) ctx.channel().remoteAddress()));
      }
    } finally {
      NettyChannel.removeChannelIfDisconnected(ctx.channel());
    }
  }

  @Override
  public void channelRead(ChannelHandlerContext ctx, Object msg) throws Exception {
    NettyChannel channel = NettyChannel.getOrAddChannel(ctx.channel());
    try {
      JSONObject jsStr = JSONObject.parseObject((String) msg);
      Request request = JSONObject.toJavaObject(jsStr, Request.class);
      // ?
      requestHandler.dispatch(request, channel);

    } catch (Exception e) {
      logger.error(e.getMessage());
    } finally {
      NettyChannel.removeChannelIfDisconnected(ctx.channel());
    }
  }

  @Override
  public void write(ChannelHandlerContext ctx, Object msg, ChannelPromise promise)
      throws Exception {
    NettyChannel channel = NettyChannel.getOrAddChannel(ctx.channel());
    try {
      // to do
      super.write(ctx, msg, promise);
    } finally {
      NettyChannel.removeChannelIfDisconnected(ctx.channel());
    }
  }

  @Override
  public void userEventTriggered(ChannelHandlerContext ctx, Object evt) throws Exception {
    // server will close channel when server don't receive any heartbeat from client util timeout.
    if (evt instanceof IdleStateEvent) {
      NettyChannel channel = NettyChannel.getOrAddChannel(ctx.channel());
      try {
        logger.info("IdleStateEvent triggered, close channel " + channel);
        channel.close();
      } finally {
        NettyChannel.removeChannelIfDisconnected(ctx.channel());
      }
    }
    super.userEventTriggered(ctx, evt);
  }

  @Override
  public void exceptionCaught(ChannelHandlerContext ctx, Throwable cause) throws Exception {
    NettyChannel channel = NettyChannel.getOrAddChannel(ctx.channel());
    try {
      ctx.close();
    } finally {
      NettyChannel.removeChannelIfDisconnected(ctx.channel());
    }
  }
}
