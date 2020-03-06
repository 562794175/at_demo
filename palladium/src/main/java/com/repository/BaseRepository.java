package com.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.JpaSpecificationExecutor;
import org.springframework.data.repository.NoRepositoryBean;

import java.io.Serializable;

/** @author a.t */
@NoRepositoryBean
public interface BaseRepository<T, PK extends Serializable>
    extends JpaRepository<T, PK>, JpaSpecificationExecutor<T>, Serializable {}
