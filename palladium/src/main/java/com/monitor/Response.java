package com.monitor;

/** @author a.t */
public class Response {
  private String param;
  private String value;
  // AUTO_STOPLOSS:1ENABLE,0DISABLED
  // OPEN_ORDER:1ENABLE
  // STOP_PROFIT:1234

  public Response() {
  }

  public Response(String param,String value) {
    this.param=param;
    this.value=value;
  }

  @Override
  public String toString() {
    return "Response{" + "param='" + param + '\'' + ", value='" + value + '\'' + '}';
  }

  public String getParam() {
    return param;
  }

  public void setParam(String param) {
    this.param = param;
  }

  public String getValue() {
    return value;
  }

  public void setValue(String value) {
    this.value = value;
  }
}
