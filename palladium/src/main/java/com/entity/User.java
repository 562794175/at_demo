package com.entity;

public class User {

  private String userName;
  private String password;
  private Integer command;
  private String tip;

  public String getUserName() {
    return userName;
  }

  public void setUserName(String userName) {
    this.userName = userName;
  }

  public String getPassword() {
    return password;
  }

  public void setPassword(String password) {
    this.password = password;
  }

  public Integer getCommand() {
    return command;
  }

  public void setCommand(Integer command) {
    this.command = command;
  }

  public String getTip() {
    return tip;
  }

  public void setTip(String tip) {
    this.tip = tip;
  }
}
