package com.monitor;

import io.netty.channel.Channel;
import io.netty.channel.ChannelFuture;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.net.InetSocketAddress;
import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ConcurrentMap;

/** @author a.t */
public class NettyChannel {

  private static final Logger logger = LoggerFactory.getLogger(NettyChannel.class);
  /** the cache for netty channel and channel */
  private static final ConcurrentMap<Channel, NettyChannel> CHANNEL_MAP =
      new ConcurrentHashMap<Channel, NettyChannel>();
  /** netty channel */
  private final Channel channel;

  private final Map<String, Object> attributes = new ConcurrentHashMap<String, Object>();
  /**
   * The constructor of NettyChannel. It is private so NettyChannel usually create by {@link
   * NettyChannel#getOrAddChannel(Channel)}
   *
   * @param channel netty channel
   */
  private NettyChannel(Channel channel) {
    this.channel = channel;
  }
  /**
   * Get channel by netty channel through channel cache. Put netty channel into it if channel don't
   * exist in the cache.
   *
   * @param ch netty channel
   * @return
   */
  static NettyChannel getOrAddChannel(Channel ch) {
    if (ch == null) {
      return null;
    }
    NettyChannel ret = CHANNEL_MAP.get(ch);
    if (ret == null) {
      NettyChannel nettyChannel = new NettyChannel(ch);
      if (ch.isActive()) {
        ret = CHANNEL_MAP.putIfAbsent(ch, nettyChannel);
      }
      if (ret == null) {
        ret = nettyChannel;
      }
    }
    return ret;
  }
  /**
   * Remove the inactive channel.
   *
   * @param ch netty channel
   */
  static void removeChannelIfDisconnected(Channel ch) {
    if (ch != null && !ch.isActive()) {
      CHANNEL_MAP.remove(ch);
    }
  }

  public InetSocketAddress getLocalAddress() {
    return (InetSocketAddress) channel.localAddress();
  }

  public InetSocketAddress getRemoteAddress() {
    return (InetSocketAddress) channel.remoteAddress();
  }

  public boolean isConnected() {
    return channel.isActive();
  }

  /**
   * Send message by netty and whether to wait the completion of the send.
   *
   * @param message message that need send.
   * @param sent whether to ack async-sent
   * @throws Exception throw RemotingException if wait until timeout or any exception thrown by
   *     method body that surrounded by try-catch.
   */
  public void send(Object message, boolean sent) throws Exception {
    boolean success = true;
    int timeout = 3;
    try {
      ChannelFuture future = channel.writeAndFlush(message);
      if (sent) {
        // wait timeout ms
        success = future.await(timeout);
      }
      Throwable cause = future.cause();
      if (cause != null) {
        throw cause;
      }
    } catch (Throwable e) {
      throw new Exception(
          "Failed to send message "
              + message
              + " to "
              + getRemoteAddress()
              + ", cause: "
              + e.getMessage(),
          e);
    }
    if (!success) {
      throw new Exception(
          "Failed to send message "
              + message
              + " to "
              + getRemoteAddress()
              + "in timeout("
              + timeout
              + "ms) limit");
    }
  }

  public void close() {
    try {
      removeChannelIfDisconnected(channel);
    } catch (Exception e) {
      logger.warn(e.getMessage(), e);
    }
    try {
      attributes.clear();
    } catch (Exception e) {
      logger.warn(e.getMessage(), e);
    }
    try {
      if (logger.isInfoEnabled()) {
        logger.info("Close netty channel " + channel);
      }
      channel.close();
    } catch (Exception e) {
      logger.warn(e.getMessage(), e);
    }
  }

  public boolean hasAttribute(String key) {
    return attributes.containsKey(key);
  }

  public Object getAttribute(String key) {
    return attributes.get(key);
  }

  public void setAttribute(String key, Object value) {
    // The null value is unallowed in the ConcurrentHashMap.
    if (value == null) {
      attributes.remove(key);
    } else {
      attributes.put(key, value);
    }
  }

  public void removeAttribute(String key) {
    attributes.remove(key);
  }

  @Override
  public int hashCode() {
    final int prime = 31;
    int result = 1;
    result = prime * result + ((channel == null) ? 0 : channel.hashCode());
    return result;
  }

  @Override
  public boolean equals(Object obj) {
    if (this == obj) {
      return true;
    }
    if (obj == null) {
      return false;
    }
    if (getClass() != obj.getClass()) {
      return false;
    }
    NettyChannel other = (NettyChannel) obj;
    if (channel == null) {
      if (other.channel != null) {
        return false;
      }
    } else if (!channel.equals(other.channel)) {
      return false;
    }
    return true;
  }

  @Override
  public String toString() {
    return "NettyChannel [channel=" + channel + "]";
  }
}
