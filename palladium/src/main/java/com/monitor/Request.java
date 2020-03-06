package com.monitor;

/** @author a.t */
public class Request {
  private String cmdName;
  private String parameter;

  public String getCmdName() {
    return cmdName;
  }

  public void setCmdName(String cmdName) {
    this.cmdName = cmdName;
  }

  public String getParameter() {
    return parameter;
  }

  public void setParameter(String parameter) {
    this.parameter = parameter;
  }

  @Override
  public String toString() {
    return "Request{" + "cmdName='" + cmdName + '\'' + ", parameter='" + parameter + '\'' + '}';
  }
}
