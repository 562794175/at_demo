package com.monitor;

import com.alibaba.fastjson.JSONObject;
import io.netty.buffer.ByteBuf;
import io.netty.buffer.Unpooled;

/** @author a.t */
public class RequestHandler {

  private static final String QUOTE = "quote";
  private static final String KLINE_TWENTY = "kline_20";
  private static final String BOLLING_TWENTY = "bolling_20";
  private static final String AC_TWENTY = "ac_20";
  private static final String OSMA_TWENTY = "osma_20";
  private static final String SAR_TWENTY = "sar_20";

  // AUTO_STOPLOSS:1ENABLE,0DISABLED
  // OPEN_ORDER:1ENABLE
  // STOP_PROFIT:1234

  public void dispatch(Request request, NettyChannel channel) throws Exception {
    switch (request.getCmdName()) {
      case QUOTE:
        doQuote(request, channel);
        break;
      case KLINE_TWENTY:
        break;
      case BOLLING_TWENTY:
        break;
      case AC_TWENTY:
        break;
      case OSMA_TWENTY:
        break;
      case SAR_TWENTY:
        break;
      default:
    }
  }

  private void doQuote(Request request, NettyChannel channel) throws Exception {

    System.out.println("do info");

    Response response = new Response();
    response.setParam("PAUSE");
    response.setValue("1");
    ByteBuf buf = Unpooled.wrappedBuffer(JSONObject.toJSONBytes(response));
    channel.send((Object) buf, true);
  }
}
