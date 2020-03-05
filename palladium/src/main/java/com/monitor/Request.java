package com.monitor;

/**
 * @author a.t
 */
public class Request {

    public String getCmdName() {
        return cmdName;
    }

    public void setCmdName(String cmdName) {
        this.cmdName = cmdName;
    }

    public Integer getParam() {
        return param;
    }

    public void setParam(Integer param) {
        this.param = param;
    }

    public Integer getValue() {
        return value;
    }

    public void setValue(Integer value) {
        this.value = value;
    }

    private String cmdName;
    private Integer param;
    private Integer value;

    @Override
    public String toString() {
        return "Request{" +
                "cmdName='" + cmdName + '\'' +
                ", param=" + param +
                ", value=" + value +
                '}';
    }

}
