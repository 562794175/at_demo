package com.entity;

import javax.persistence.Column;
import javax.persistence.Entity;

/**
 * @author a.t
 */
@Entity
public class OsmaTwenty extends BaseEntity {

    @Column(nullable = false, columnDefinition="text")
    private String value;

}
