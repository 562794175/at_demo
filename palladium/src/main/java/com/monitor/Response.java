package com.monitor;

/** @author a.t */
public class Response {
  private String param;
  private String value;

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
