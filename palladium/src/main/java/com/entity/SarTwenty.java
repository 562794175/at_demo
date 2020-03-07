package com.entity;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Index;
import javax.persistence.Table;

/**
 * @author a.t
 */
@Entity
@Table(indexes = {@Index(columnList = "symbol,duration,firstAxis,secondAxis",name="twenty_uindex",unique = true)})
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
