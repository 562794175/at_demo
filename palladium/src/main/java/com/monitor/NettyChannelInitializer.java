package com.monitor;

import io.netty.channel.ChannelInitializer;
import io.netty.channel.socket.nio.NioSocketChannel;
import io.netty.handler.codec.json.JsonObjectDecoder;
import io.netty.handler.codec.string.StringDecoder;

import java.nio.charset.Charset;

/**
 * @author a.t
 */
public class NettyChannelInitializer extends ChannelInitializer<NioSocketChannel> {

    private final NettyChannelHandler nettyChannelHandler;

    public NettyChannelInitializer(NettyChannelHandler nettyChannelHandler) {
        this.nettyChannelHandler = nettyChannelHandler;
    }

    @Override
    protected void initChannel(NioSocketChannel ch) throws Exception {

        ch.pipeline().addLast("jsonObjectDecoder",new JsonObjectDecoder());
        ch.pipeline().addLast("stringDecoder", new StringDecoder(Charset.forName("utf-8")));
        ch.pipeline().addLast("handler", nettyChannelHandler);
    }

}
