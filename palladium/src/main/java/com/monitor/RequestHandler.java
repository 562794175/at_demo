package com.monitor;

import com.alibaba.fastjson.JSONObject;
import com.entity.AcTwenty;
import com.repository.AcTwentyRepository;
import io.netty.buffer.ByteBuf;
import io.netty.buffer.Unpooled;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Component;

import javax.annotation.Resource;

/** @author a.t */
@Component
public class RequestHandler {

  private static final String QUOTE = "quote";
  private static final String KLINE_TWENTY = "kline_20";
  private static final String BOLLING_TWENTY = "bolling_20";
  private static final String AC_TWENTY = "ac_20";
  private static final String OSMA_TWENTY = "osma_20";
  private static final String SAR_TWENTY = "sar_20";
  private final Logger logger = LoggerFactory.getLogger(this.getClass());

  @Resource private AcTwentyRepository acTwentyRepository;

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
        doAc(request, channel);
        break;
      case OSMA_TWENTY:
        break;
      case SAR_TWENTY:
        break;
      default:
    }
  }

  private void doAc(Request request, NettyChannel channel) {
    String parameter = String.format("{%s}", request.getParameter());
    JSONObject jsStr = JSONObject.parseObject(parameter);
    AcTwenty acTwenty = jsStr.toJavaObject(AcTwenty.class);
    acTwentyRepository.saveAndFlush(acTwenty);
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
