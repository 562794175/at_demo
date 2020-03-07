package com.monitor;

import com.alibaba.fastjson.JSONObject;
import com.entity.*;
import com.repository.*;
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

  @Resource private QuoteRepository quoteRepository;
  @Resource private AcTwentyRepository acTwentyRepository;
  @Resource private KlineTwentyRepository klineTwentyRepository;
  @Resource private BollingTwentyRepository bollingTwentyRepository;
  @Resource private OsmaTwentyRepository osmaTwentyRepository;
  @Resource private SarTwentyRepository sarTwentyRepository;

  @Resource private NettyServer nettyServer;

  public void dispatch(Request request, NettyChannel channel) throws Exception {
    switch (request.getCmdName()) {
      case QUOTE:
        doQuote(request, channel);
        break;
      case KLINE_TWENTY:
        doKlineTwenty(request, channel);
        break;
      case BOLLING_TWENTY:
        doBollingTwenty(request, channel);
        break;
      case AC_TWENTY:
        doAcTwenty(request, channel);
        break;
      case OSMA_TWENTY:
        doOsmaTwenty(request, channel);
        break;
      case SAR_TWENTY:
        doSarTwenty(request, channel);
        break;
      default:
    }
  }

  private void doQuote(Request request, NettyChannel channel) throws Exception {
    String parameter = String.format("{%s}", request.getParameter());
    JSONObject jsStr = JSONObject.parseObject(parameter);
    Quote quote = jsStr.toJavaObject(Quote.class);
    nettyServer.setQuote(quote);
  }

  private void doSarTwenty(Request request, NettyChannel channel) {
    String parameter = String.format("{%s}", request.getParameter());
    JSONObject jsStr = JSONObject.parseObject(parameter);
    SarTwenty sarTwenty = jsStr.toJavaObject(SarTwenty.class);
    sarTwentyRepository.saveAndFlush(sarTwenty);
  }

  private void doOsmaTwenty(Request request, NettyChannel channel) {
    String parameter = String.format("{%s}", request.getParameter());
    JSONObject jsStr = JSONObject.parseObject(parameter);
    OsmaTwenty osmaTwenty = jsStr.toJavaObject(OsmaTwenty.class);
    osmaTwentyRepository.saveAndFlush(osmaTwenty);
  }

  private void doBollingTwenty(Request request, NettyChannel channel) {
    String parameter = String.format("{%s}", request.getParameter());
    JSONObject jsStr = JSONObject.parseObject(parameter);
    BollingTwenty klineTwenty = jsStr.toJavaObject(BollingTwenty.class);
    bollingTwentyRepository.saveAndFlush(klineTwenty);
  }

  private void doKlineTwenty(Request request, NettyChannel channel) {
    String parameter = String.format("{%s}", request.getParameter());
    JSONObject jsStr = JSONObject.parseObject(parameter);
    KlineTwenty klineTwenty = jsStr.toJavaObject(KlineTwenty.class);
    klineTwentyRepository.saveAndFlush(klineTwenty);
  }

  private void doAcTwenty(Request request, NettyChannel channel) {
    String parameter = String.format("{%s}", request.getParameter());
    JSONObject jsStr = JSONObject.parseObject(parameter);
    AcTwenty acTwenty = jsStr.toJavaObject(AcTwenty.class);
    acTwentyRepository.saveAndFlush(acTwenty);
  }
}
