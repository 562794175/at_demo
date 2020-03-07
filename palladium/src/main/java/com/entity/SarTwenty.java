package com.entity;

import javax.persistence.Column;
import javax.persistence.Entity;

/**
 * @author a.t
 */
@Entity
public class SarTwenty extends BaseEntity {

    public String getValue() {
        return value;
    }

    public void setValue(String value) {
        this.value = value;
    }

    @Column(nullable = false, columnDefinition="text")
    private String value;

}
